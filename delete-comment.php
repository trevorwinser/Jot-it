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

$servername = "localhost";
$username = "61837175";
$password = "61837175";
$dbname = "db_61837175";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
