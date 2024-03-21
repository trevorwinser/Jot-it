<?php

include 'navbar.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jot-it";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo '<link rel="stylesheet" href="css/post.css">';

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM post WHERE id = ?");
    $stmt->bind_param('i', $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
        
        if (!empty($post['image'])) {
            $image_data = base64_encode($post['image']);
            $post['image'] = $image_data;
        }
        echo '<div class="post">';
        echo '<h2>' . htmlspecialchars($post['title']) . '</h2>';  
        echo '<p>' . nl2br(htmlspecialchars($post['body'])) . '</p>';
        if (!empty($post['image'])) {
            echo '<img src="data:image/jpeg;base64,' . $post['image'] . '" alt="Post Image">';
        }
        echo '</div>';
        
    } else {
        echo '<div class="error">Post not found</div>';
    }
} else {
    echo '<div class="error">Post ID not provided</div>';
}

$conn->close();
?>
