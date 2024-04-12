<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Comment</title>
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
<?php
include 'navbar.php';
include 'verify-admin.php';

$message = $_GET['message'] ?? '';
$comment_id = $_GET['comment_id'] ?? '';

    
include 'conn.php';

$stmt = $conn->prepare("SELECT body FROM comment WHERE id = ?");
$stmt->bind_param("i", $comment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $comment = $result->fetch_assoc();
} else {
    $message = 'Comment not found';
    $comment['body'] = '';
}

$stmt->close();
$conn->close();
?>
<div>
    <form action="edit-comment-submit.php" method="post">
        <h2>Edit Comment</h2>
        <p id="message"><?php echo $message; ?></p>
        <input type="hidden" name="comment_id" value="<?php echo $comment_id; ?>">
        <label for="body">Body:</label><br>
        <textarea id="body" name="body" rows="4" cols="50"><?php echo $comment['body']; ?></textarea><br><br>
        <input type="submit" value="Save">
    </form>
</div>
</body>
</html>
