<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
<?php
include 'navbar.php';
include 'verify-admin.php';

$message = $_GET['message'] ?? '';
$post_id = $_GET['post_id'] ?? '';

    
include 'conn.php';

$stmt = $conn->prepare("SELECT title, body FROM post WHERE id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $post = $result->fetch_assoc();
} else {
    $message = 'Post not found';
    $post['body'] = '';
    $post['title'] = '';
}

$stmt->close();
$conn->close();
?>
<div>
    <form action="edit-post-submit.php" method="post">
        <h2>Edit Post</h2>
        <p id="message"><?php echo $message; ?></p>
        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
        <label for="title">Title:</label><br>
        <textarea id="title" name="title" style="resize: vertical; overflow: auto; width:100%; min-height: 20px; max-height: 38px;"><?php echo $post['title']; ?></textarea><br><br>
        <label for="body">Body:</label><br>
        <textarea id="body" name="body" style="resize: vertical; overflow: auto; width:100%; min-height: 150px; max-height: 500px;"><?php echo $post['body']; ?></textarea><br><br>
        <input type="submit" value="Save">
    </form>
</div>
</body>
</html>
