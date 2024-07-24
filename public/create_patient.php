<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$name = $input['name'];
$code = $input['code'];
$severity = $input['severity'];

$servername = "localhost";
$db_username = getenv('DB_USERNAME');
$db_password = getenv('DB_PASSWORD');
$dbname = "hospital_db";

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
