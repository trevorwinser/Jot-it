<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['post_id']) && !empty($_POST['post_id'])) {
        $post_id = $_POST['post_id'];
        if (isset($_POST['title'],$_POST['title']) && !empty($_POST['title']) && !empty($_POST['body'])) {
            $title = $_POST['title'];
            $body = $_POST['body'];
        
            include 'conn.php';

            try {
                $stmt = $conn->prepare("UPDATE post SET title = ?, body = ? WHERE id = ?");
                $stmt->bind_param('ssi', $title, $body, $post_id);
                $stmt->execute();
                $stmt->close();

                $conn->close();
                
                // Redirect back on successful update.
                header("Location: edit-post.php?post_id=$post_id&message=Post updated successfully");
                exit();
            } catch (mysqli_sql_exception $e) {
                // Handle any exceptions or errors here
                header("Location: edit-post.php?post_id=$post_id&message=An error occurred");
                exit();
            }
        } else {
            header("Location: edit-post.php?post_id=$post_id&message=Title and body must contain text");
        }
    } else {
        header("Location: admin.php");  //Should not happen but if post_id is not set, return to admin page.
    }
} else {
    header("Location: admin.php");
    exit();
}
?>
