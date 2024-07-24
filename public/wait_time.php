<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$name = $input['name'];
$code = strtoupper($input['code']);

$servername = "localhost";
$db_username = "root";
$db_password = "uottawa!123";
$dbname = "hospital_db";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['waitTime' => 'Error: Connection failed']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($input['action'] === 'checkWaitTime') {
        $check_sql = "SELECT * FROM patients WHERE name = ? AND code = ? AND status = 'waiting'";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ss", $name, $code);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $sql = "SELECT SUM(severity * 10) AS total_wait_time
                    FROM patients
                    WHERE status = 'waiting'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $total_wait_time = $row['total_wait_time'];
                echo json_encode(['waitTime' => $total_wait_time . ' minutes']);
            } else {
                echo json_encode(['waitTime' => 'No records found']);
            }

            $stmt->close();
        } else {
            echo json_encode(['waitTime' => 'No matching patient record found']);
        }

        $check_stmt->close();
    } else {
        echo json_encode(['waitTime' => 'Invalid action']);
    }
}

$conn->close();
?>
