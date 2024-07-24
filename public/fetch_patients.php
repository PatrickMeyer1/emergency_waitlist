<?php
header('Content-Type: application/json');

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

// Get the filter parameter from the query string
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Prepare the SQL query based on the filter
if ($filter === 'all') {
    $sql = "SELECT id, name, severity, TIMESTAMPDIFF(MINUTE, timestamp, NOW()) as time_in_queue, status FROM patients ORDER BY severity DESC, timestamp ASC";
} else {
    $sql = "SELECT id, name, severity, TIMESTAMPDIFF(MINUTE, timestamp, NOW()) as time_in_queue, status FROM patients WHERE status = ? ORDER BY severity DESC, timestamp ASC";
}

// Prepare and execute the statement
$stmt = $conn->prepare($sql);
if ($filter !== 'all') {
    $stmt->bind_param("s", $filter);
}
$stmt->execute();
$result = $stmt->get_result();

$patients = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $patients[] = $row;
    }
}

echo json_encode(['patients' => $patients]);

$stmt->close();
$conn->close();
?>
