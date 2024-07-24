<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$name = $input['name'];
$code = $input['code'];

$servername = "localhost";
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$dbname = "hospital_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    (json_encode(['waitTime' => 'Error: Connection failed']));
}

$sql = "SELECT TIMESTAMPDIFF(MINUTE, timestamp, NOW()) as wait_time FROM patients WHERE name = ? AND code = ? AND status = 'waiting' ORDER BY severity DESC, timestamp ASC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $name, $code);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $wait_time = $row['wait_time'];
    echo json_encode(['waitTime' => $wait_time . ' minutes']);
} else {
    echo json_encode(['waitTime' => 'No records found']);
}

$stmt->close();
$conn->close();
?>
