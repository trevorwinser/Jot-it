<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $comment_id = $_POST['comment_id'];
    if (!empty($_POST['body'])) {
        $body = $_POST['body'];
    
        $servername = "localhost";
        $username = "61837175";
        $password = "61837175";
        $dbname = "db_61837175";

        $conn = new mysqli($servername, $username_db, $password_db, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

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
    }
} else {
    header("Location: admin.php");
    exit();
}
?>
