<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

$servername = "localhost";
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$dbname = "hospital_db";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    echo json_encode(['status' => 'Error: Connection failed', 'error' => $conn->connect_error]);
    exit;
}

$sql = "SELECT id, password FROM admins WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        $_SESSION['admin_id'] = $row['id'];
        echo json_encode(['status' => 'Admin signed in successfully']);
    } else {
        echo json_encode(['status' => 'Invalid username or password']);
    }
} else {
    echo json_encode(['status' => 'Invalid username or password']);
}

$stmt->close();
$conn->close();
?>
