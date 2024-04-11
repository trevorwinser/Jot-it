<?php
session_start();
include 'verify-admin.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php"); // Redirect non-admin users to login page
    exit();
}

if (!isset($_GET['post_id'])) {
    header("Location: admin.php"); // Redirect if post_id is not provided
    exit();
}

$post_id = $_GET['post_id'];

include 'conn.php';

$stmt = $conn->prepare("DELETE FROM post WHERE id = ?");
$stmt->bind_param("i", $post_id);

if ($stmt->execute()) {
    header("Location: admin.php"); // Redirect to admin page after deleting
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>
