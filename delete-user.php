<?php
include 'verify-admin.php';

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "jot-it";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "DELETE FROM user WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) { 
        header("Location: admin.php?delete_status=success");
        exit();
    } else {
        header("Location: admin.php?delete_status=error");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
