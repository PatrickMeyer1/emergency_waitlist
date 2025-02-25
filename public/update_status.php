<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$id = $input['id'] ?? null;
$status = $input['status'] ?? null;

$config = include('config.php');
$servername = $config['servername'];
$db_username = $config['db_username'];
$db_password = $config['db_password'];
$dbname = $config['dbname'];

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Connection failed']);
    exit;
}

if ($id && $status) {
    $sql = "UPDATE patients SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Status updated']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update status']);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}

$conn->close();
?>
