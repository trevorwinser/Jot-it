<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
</head>
<body>
<?php
include 'navbar.php';
$servername = "localhost";
$username = "61837175";
$password = "61837175";
$dbname = "db_61837175";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo '<link rel="stylesheet" href="css/post.css">';
if (isset($_GET['id'])) {
    $post_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT p.*, u.username AS poster_username FROM post p JOIN user u ON p.user_id = u.id WHERE p.id = ?");
    $stmt->bind_param('i', $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
        

        echo '<div class="post">';
        echo '<button id="myButton" class="float-left submit-button" >Category: ';

        switch ($post['category']) {
            case 1:
                echo 'Art';
                break;
            case 2:
                echo 'Food';
                break;
            case 3:
                echo 'Sports';
                break;
            case 4:
                echo 'Travel';
                break;
            default:
                echo 'Unknown';
        }

        echo '</button>';
        echo '<script type="text/javascript"> document.getElementById("myButton").onclick = function () {';
        echo 'location.href = "categories.php?category='.$post['category'].'";';
        echo '};';
        echo '</script>';

        echo '<h2>' . htmlspecialchars($post['title']) . '</h2>';  
        echo '<p>' . nl2br(htmlspecialchars($post['body'])) . '</p>';
        echo '<p>Posted by: ' . htmlspecialchars($post['poster_username']) . '</p>';
        if (!empty($post['image'])) {
            echo '<img src="data:image/jpeg;base64,' . base64_encode($post['image']) . '" alt="Post Image">';
        }
        echo '</div>';

        echo '<div class="comments-container">';
        echo '<script>$(document).ready(function() { loadComments(); });</script>';
        echo '</div>';

        if (isset($_GET['id']) && isset($_SESSION['id'])) {
            echo '<div class="submitCommentContainer">';
            echo '<form id="commentForm" method="post" action="">';
            echo '<div class="comment-container">';
            echo '<label for="comment-box">Type your comment</label><br>';
            echo '<textarea name="comment-box" id="comment-box"></textarea>';
            echo '</div>';
            echo '<button type="submit" class="submit-btn" id="submit-btn">Post Comment</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
        }
        
    }
}
$conn->close();
?>

<script>
$(document).ready(function() {
    $('#commentForm').submit(function(e) {
        e.preventDefault(); 
        
        var postId = <?php echo isset($_GET['id']) ? $_GET['id'] : 'null'; ?>;
        var commentText = $('#comment-box').val();
        
        console.log('postId:', postId); 
        console.log('commentText:', commentText); 
        if (postId && commentText) {
            $.ajax({
                type: 'POST',
                url: 'submit-comments.php',
                data: {
                    post_id: postId,
                    commentText: commentText
                },
                success: function(response) {
                    if (response.success) {
                        $('#comment-box').val(''); 
                        loadComments(); 
                    } else {
                        alert('Error posting comment: ' + response.error);
                    }
                },
                dataType: 'json'
            });
        }
    });
    function loadComments() {
        var postId = <?php echo isset($_GET['id']) ? $_GET['id'] : 'null'; ?>;
        if (postId) {
            $.getJSON('fetch-comments.php?id=' + postId, function(data) {
                var commentsContainer = $('.comments-container');
                commentsContainer.empty();
                if (data.length > 0) {
                    commentsContainer.append('<h3>Comments</h3>');
                    $.each(data, function(index, comment) {
                        commentsContainer.append('<span class="comment"><b>' + comment.username + '</b>: ' + comment.body + '</span><br><br><br>');
                    });
                } else {
                    commentsContainer.append('<p>No comments yet.</p>');
                }
            });
        }
    }

    loadComments();
});
</script>

</body>
</html>
