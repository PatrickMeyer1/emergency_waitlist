<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$admin_username = $input['admin_username'] ?? null;
$admin_password = $input['admin_password'] ?? null;

if (empty($admin_username) || empty($admin_password)) {
    echo json_encode(['status' => 'error', 'message' => 'Error: Missing username or password']);
    exit;
}

$config = include('config.php');
$servername = $config['servername'];
$db_username = $config['db_username'];
$db_password = $config['db_password'];
$dbname = $config['dbname'];

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Error: Connection failed', 'error' => $conn->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($input['action'] === 'adminSignIn') {
        $sql = "SELECT * FROM admins WHERE username = ? AND password = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $admin_username, $admin_password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo json_encode(['status' => 'success', 'message' => 'Admin signed in successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid username or password']);
        }
    } else {
    echo json_encode(['status' => 'Invalid action']);
    }
}
$conn->close();
?>
