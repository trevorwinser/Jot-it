<?php
header('Content-Type: application/json');
$servername = "localhost";
$username = "61837175";
$password = "61837175";
$dbname = "db_61837175";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
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
    echo json_encode([]);
}
$conn->close();
?>
