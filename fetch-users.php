<?php
// Fetch user data and return as JSON
$user_id = $_GET['user_id'] ?? '';
$username = '';
$enabled = '';


$servername = "localhost";
$username = "61837175";
$password = "61837175";
$dbname = "db_61837175";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute SQL query to fetch user details
$stmt = $conn->prepare("SELECT username, enabled FROM user WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row['username'];
    $enabled = $row['enabled'];
} else {
    // User not found
    $message = 'User not found';
}

$stmt->close();
$conn->close();

// Prepare data to return as JSON
$response = [
    'message' => $message ?? 'User found', // Assuming $message is set if user not found
    'username' => $username,
    'enabled' => $enabled,
];

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
