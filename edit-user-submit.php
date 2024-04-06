<?php
session_start();
include 'verify-admin.php';
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "jot-it";

    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $new_username = $_POST['new_username'];
    $enabled = isset($_POST['enabled']) ? 1 : 0;

    try {
        if (!empty($new_username)) {
            $stmt = $conn->prepare("UPDATE user SET username = ? WHERE id = ?");
            $stmt->bind_param('si', $new_username, $user_id);
            $stmt->execute();
            $stmt->close();
        }

        $stmt = $conn->prepare("UPDATE user SET enabled = ? WHERE id = ?");
        $stmt->bind_param('ii', $enabled, $user_id);
        $stmt->execute();
        $stmt->close();

        $conn->close();

        // Redirect back on successful update.
        header("Location: edit-user.php?user_id=$user_id&message=Profile updated successfully");
        exit();
    } catch (mysqli_sql_exception $e) {
        //Error code for when username is taken
        if ($e->getCode() == 1062) {
            header("Location: edit-user.php?user_id=$user_id&message=Username is taken");
            exit();
        } else {
            header("Location: edit-user.php?user_id=$user_id&message=An error occurred");
            exit();
        }
    }
} else {
    header("Location: edit-user.php");
    exit();
}
?>
