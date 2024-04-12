<?php

include 'conn.php';

// Skip redirect logic if this script is included from login.php
$currentPage = basename($_SERVER['PHP_SELF']);
if ($currentPage === 'login.php') {
    return; // Skip further processing in this script
}

// Check if username is set in the session
if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    $stmt = $conn->prepare("SELECT id FROM user WHERE username = ?;");
    $stmt->bind_param('s', $username);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        // Fetch result and set user_id to session if not already set
        if ($row = $result->fetch_assoc()) {
            $_SESSION['user_id'] = $row['id']; // This line ensures the user_id is set in the session

            // Insert login activity into the user_activity table
            $stmtActivity = $conn->prepare("INSERT INTO user_activity (user_id) VALUES (?)");
            $stmtActivity->bind_param("i", $_SESSION['user_id']);
            $stmtActivity->execute();
            $stmtActivity->close();
        } else {
            // Redirect to login page with error if username is not found in the database
            header("Location: login.php?message=Invalid session, please login again");
            exit();
        }
    } else {
        // Redirect to login if execution fails
        header("Location: login.php?message=Log in required");
        exit();
    }
    $stmt->close();
} else {
    // Redirect to login if username is not set in the session
    header("Location: login.php?message=Log in required");
    exit();
}

// Close the database connection at the end of your script
$conn->close();

?>
