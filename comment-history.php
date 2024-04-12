<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comment History</title>
    <link rel="stylesheet" href="css/comment.css">
</head>
<body>

    <?php 
    include 'navbar.php'; 
    include 'verify-login.php';
    include 'conn.php';

    $user_id = $_SESSION['id'];
    $stmt = $conn->prepare("SELECT c.body AS comment_body, p.title AS post_title, p.id AS post_id
                            FROM comment c 
                            JOIN post p ON c.post_id = p.id 
                            WHERE c.commenter_id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <div class="container">
        <h2>Comment History</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Comment</th>
                        <th>Post</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['comment_body']; ?></td>
                            <td><?php echo '<a href="postDetails.php?id='.$row['post_id'].'">'.$row['post_title'].'</a>'; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No comments found.</p>
        <?php endif; ?>
    </div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
