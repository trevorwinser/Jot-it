<?php
session_start();
include 'verify-admin.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php"); // Redirect non-admin users to login page
    exit();
}

if (!isset($_GET['comment_id'])) {
    header("Location: admin.php"); // Redirect if comment_id is not provided
    exit();
}

$comment_id = $_GET['comment_id'];

include 'conn.php';

$stmt = $conn->prepare("DELETE FROM comment WHERE id = ?");
$stmt->bind_param("i", $comment_id);

if ($stmt->execute()) {
    header("Location: admin.php"); // Redirect to admin page after deleting
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>
