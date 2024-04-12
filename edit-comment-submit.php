<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['comment_id'], $_POST['body'])) {
        $comment_id = $_POST['comment_id'];
        $body = $_POST['body'];
    
        if (!empty($body)) {
            
            include 'conn.php';

            try {
                $stmt = $conn->prepare("UPDATE comment SET body = ? WHERE id = ?");
                $stmt->bind_param('si', $body, $comment_id);
                $stmt->execute();
                $stmt->close();

                $conn->close();

                // Redirect back on successful update.
                header("Location: edit-comment.php?comment_id=$comment_id&message=Comment updated successfully");
                exit();
            } catch (mysqli_sql_exception $e) {
                // Handle any exceptions or errors here
                header("Location: edit-comment.php?comment_id=$comment_id&message=An error occurred");
                exit();
            }
        } else {
            header("Location: edit-comment.php?comment_id=$comment_id&message=Body must contain text");
            exit();
        }
    } else {
        header("Location: admin.php");
        exit();
    }
} else {
    header("Location: admin.php");
    exit();
}
?>
