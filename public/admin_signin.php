<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

$admin_username = $input['admin_username'] ?? null;
$admin_password = $input['admin_username'] ?? null;

$servername = "localhost";
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$dbname = "hospital_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['status' => 'Error: Connection failed', 'error' => $conn->connect_error]);
    exit;
}

// SQL query to select all admins and their passwords
$sql = "SELECT username, password FROM admins";

// Execute the SQL query
$result = $conn->query($sql);

// Check if query was successful
if ($result === false) {
    echo json_encode(['status' => 'error', 'message' => 'Error: Query failed', 'error' => $conn->error]);
    $conn->close();
    exit;
}

// Fetch all results and print them
$admins = [];
while ($row = $result->fetch_assoc()) {
    $admins[] = $row;
}

// Output the result as JSON
echo json_encode(['status' => 'success', 'admins' => $admins]);

// Close the connection
$conn->close();
?>
