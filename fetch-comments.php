<?php
header('Content-Type: application/json');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jot-it";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Check if post_id is set in the request
if (isset($_GET['id'])) {
    // Use prepared statement to prevent SQL injection
    $post_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT c.*, u.username FROM comment c JOIN user u ON c.commenter_id = u.id WHERE c.post_id = ?");
    $stmt->bind_param('i', $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result !== false) {
        $comments = [];
        while ($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }
        echo json_encode($comments);
    } else {
        echo json_encode([]);
    }
} else {
    // Handle case where post_id is not provided in the request
    echo json_encode([]);
}
$conn->close();
?>
