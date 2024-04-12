<?php
session_start(); // Start the session

// Check if user is logged in
if(isset($_SESSION['id'])) {
    // Check if post_id is set
    if(isset($_POST['post_id'])) {
        // Include the database connection
        include 'conn.php';

        // Get user id and post id
        $user_id = $_SESSION['id'];
        $post_id = $_POST['post_id'];

        // Check if a row exists in the likes table for the current user and post
        $stmt = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND post_id = ?");
        $stmt->bind_param('ii', $user_id, $post_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // If a row exists, delete it (unlike)
        if($result->num_rows > 0) {
            $delete_stmt = $conn->prepare("DELETE FROM likes WHERE user_id = ? AND post_id = ?");
            $delete_stmt->bind_param('ii', $user_id, $post_id);
            $delete_stmt->execute();

            // Decrement likes counter in the post table
            $update_stmt = $conn->prepare("UPDATE post SET likes = likes - 1 WHERE id = ?");
            $update_stmt->bind_param('i', $post_id);
            $update_stmt->execute();

            // Redirect back to postDetails.php with post_id parameter
            header("Location: postDetails.php?id=$post_id");
            exit();
        } else {
            // If no row exists, insert a new row (like)
            $insert_stmt = $conn->prepare("INSERT INTO likes (user_id, post_id, date) VALUES (?, ?, ?)");
            $datetime = date('Y-m-d H:i:s');
            $insert_stmt->bind_param('iis', $user_id, $post_id, $datetime);
            $insert_stmt->execute();

            // Increment likes counter in the post table
            $update_stmt = $conn->prepare("UPDATE post SET likes = likes + 1 WHERE id = ?");
            $update_stmt->bind_param('i', $post_id);
            $update_stmt->execute();

            // Redirect back to postDetails.php with post_id parameter
            header("Location: postDetails.php?id=$post_id");
            exit();
        }

        // Close prepared statements
        $stmt->close();
        $insert_stmt->close();
        $delete_stmt->close();
        $update_stmt->close();

        // Close database connection
        $conn->close();
    } else {
        // Redirect back to postDetails.php without post_id parameter
        header("Location: postDetails.php");
        exit();
    }
} else {
    // Redirect back to postDetails.php without post_id parameter
    header("Location: postDetails.php");
    exit();
}
?>
