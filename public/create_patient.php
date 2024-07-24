<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$name = $input['name'];
$code = $input['code'];
$severity = $input['severity'];

$config = include('config.php');
$servername = $config['servername'];
$db_username = $config['db_username'];
$db_password = $config['db_password'];
$dbname = $config['dbname'];

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['status' => 'Error: Connection failed']));
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($input['action'] === 'addPatient') {
        $sql = "INSERT INTO patients (name, code, severity, status, timestamp) VALUES (?, ?, ?, 'waiting', NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $name, $code, $severity);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'Patient added successfully']);
        } else {
            echo json_encode(['status' => 'Error: Failed to add patient']);
        }

        $stmt->close();
        $conn->close();
    }
}

?>
