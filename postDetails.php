<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Details</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/post.css">
</head>
<body>
    <?php
    include 'navbar.php';
    include 'conn.php';

    if (isset($_GET['id'])) {
        $post_id = $_GET['id'];

        if (isset($_SESSION['id'])) {
            $user_id = $_SESSION['id'];
            $stmt = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND post_id = ?");
            $stmt->bind_param('ii', $user_id, $post_id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if a row exists for the current user and post
            if ($result->num_rows > 0) {
                // Display unlike button
                echo '<form class="like-button" method="post" action="like.php">';
                echo '<input type="hidden" name="post_id" value="' . $post_id . '">';
                echo '<button type="submit">Unlike</button>';
                echo '</form>';
            } else {
                // Display like button
                echo '<form class="like-button" method="post" action="like.php">';
                echo '<input type="hidden" name="post_id" value="' . $post_id . '">';
                echo '<button type="submit">Like</button>';
                echo '</form>';
            }
        }

        $stmt = $conn->prepare("SELECT p.*, u.username AS poster_username FROM post p JOIN user u ON p.user_id = u.id WHERE p.id = ?");
        $stmt->bind_param('i', $post_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $post = $result->fetch_assoc();

            echo '<div class="post">';
            echo '<button><a href="categories.php?category=' . $post['category'] . '" class="float-left submit-button" id="myButton">Category: ';
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
            echo '</a></button>';
            echo '<button style="padding-left:20px;">Likes: '.$post['likes'].'</button>';
            echo '<h2>' . htmlspecialchars($post['title']) . '</h2>';
            echo '<p>' . nl2br(htmlspecialchars($post['body'])) . '</p>';
            echo '<p>Posted by: ' . htmlspecialchars($post['poster_username']) . '</p>';
            if (!empty($post['image'])) {
                echo '<img src="data:image/jpeg;base64,' . base64_encode($post['image']) . '" alt="Post Image">';
            }
            echo '</div>';

            // Add button to toggle comments visibility
            echo '<button id="toggleComments" type="button">Toggle Comments</button>';
            echo '<div id="commentsSection" style="display: none;">';
            echo '<div class="comments-container"></div>';
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
            }

            $conn->close();
        }
    }
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

            // Toggle comments visibility
            $('#toggleComments').click(function() {
                $('#commentsSection').toggle();
            });
        });
    </script>
</body>
</html>
