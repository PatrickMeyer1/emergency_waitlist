<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$dbname = "hospital_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['status' => 'Error: Connection failed']));
}

$sql = "SELECT name, severity, TIMESTAMPDIFF(MINUTE, timestamp, NOW()) as time_in_queue, status FROM patients WHERE status = 'waiting' ORDER BY severity DESC, timestamp ASC";
$result = $conn->query($sql);

$patients = [];
while ($row = $result->fetch_assoc()) {
    $patients[] = $row;
}

echo json_encode($patients);

$conn->close();
?>
