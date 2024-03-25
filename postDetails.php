<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
</head>
<body>
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
ob_start();
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


        // Comments
        echo '<div class="comments-container">';
        $comments_query = $conn->prepare("SELECT c.*, u.username FROM comment c JOIN user u ON c.commenter_id = u.id WHERE c.post_id = ?");
        if (!$comments_query) {
            die("Error preparing comments query: " . $conn->error);
        }
        $comments_query->bind_param('i', $post_id);
        $comments_query->execute();
        $comments_result = $comments_query->get_result();

        if (!$comments_result) {
            die("Error executing comments query: " . $conn->error);
        }
        if ($comments_result->num_rows > 0) {
            echo '<h3>Comments</h3>';
            while ($comment = $comments_result->fetch_assoc()) {
                echo '<span class="comment">';
                echo '<b>' . htmlspecialchars($comment['username']) . '</b>: ' . htmlspecialchars($comment['body']);
                echo '</span><br><br><br>';
            }
        } else {
            echo '<p>No comments yet.</p>';
        }

        // Comment form
        echo '<div class="submitCommentContainer">';
        echo '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">';
        echo '<div class="comment-container">';
        echo '<label for="comment-box">Type your comment</label><br>';
        echo '<textarea name="comment-box" id="comment-box"></textarea>';
        echo '</div>';
        echo '<button type="submit" class="submit-btn">Post Comment</button>';
        echo '</form>';
        echo '</div>';
        echo '</div>';

        // Handle comment submission
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment-box'])) {
            $comment_text = $_POST['comment-box'];
            if(isset($_SESSION['id'])) {
                $commenter_id = $_SESSION['id'];
            }else {
                echo "User not authenticated";
                echo  $_SESSION['id'];
                exit;
            }
            $datetime = date("Y-m-d H:i:s");

            // Insert comment into the database
            $insert_comment_stmt = $conn->prepare("INSERT INTO comment (post_id, body, commenter_id, date) VALUES (?, ?, ?, ?)");
            $insert_comment_stmt->bind_param('isss', $post_id, $comment_text, $commenter_id,$datetime);

            if ($insert_comment_stmt->execute()) {
                echo '<p class="success">Comment posted successfully.</p>';
            } else {
                echo '<p class="error">Error posting comment.</p>';
                
            }
            header("Location: ".$_SERVER['REQUEST_URI']);
            exit;
        }
        ob_end_flush();
        
        echo '<title>'. $post['title'].'</title>';
    } else {
        echo '<div class="error"><h1>Post not found</h1></div>';
    }
} else {
    echo '<div class="error"><h1>Post ID not provided</h1></div>';
}
$conn->close();
?>

</body>
</html>
