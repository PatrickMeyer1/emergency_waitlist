<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$name = $input['name'];
$code = strtoupper($input['code']);

$config = include('config.php');
$servername = $config['servername'];
$db_username = $config['db_username'];
$db_password = $config['db_password'];
$dbname = $config['dbname'];

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['waitTime' => 'Error: Connection failed']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($input['action'] === 'checkWaitTime') {
        $check_sql = "SELECT id, severity, status FROM patients WHERE name = ? AND code = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ss", $name, $code);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $patient = $check_result->fetch_assoc();

            if ($patient['status'] === 'treated') {
                echo json_encode(['waitTime' => 'Patient has already been treated']);
            } else {
                $sql = "SELECT COUNT(*) * 10 AS total_wait_time
                        FROM patients
                        WHERE status = 'waiting' AND(severity > ? OR (severity = ? AND id < ?))";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iii", $patient['severity'], $patient['severity'], $patient['id']);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $total_wait_time = $row['total_wait_time'];
                    error_log($total_wait_time);
                    echo json_encode(['waitTime' => $total_wait_time . ' minutes']);
                } else {
                    echo json_encode(['waitTime' => 'No records found']);
                }

                $stmt->close();
            }

            $check_stmt->close();
        } else {
            echo json_encode(['waitTime' => 'No matching patient record found']);
        }
    } else {
        echo json_encode(['waitTime' => 'Invalid action']);
    }
}

$conn->close();
?>
