<?php
session_start(); 

// Assuming $_SESSION['user_id'] contains the ID of the logged-in user
if (isset($_SESSION['user_id'])) {
    // Record user activity in the database.
    $stmt = $conn->prepare("INSERT INTO user_activity (user_id) VALUES (?)");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();

    // Redirect to the home page after recording login activity
    header("Location: home.php");
    exit();
} else {
    // Handle the case where the user ID is not set
    echo "User ID not found. Please log in again.";
}
?>
