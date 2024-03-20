<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jot-it";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Prepare statement
    $stmt = $conn->prepare("INSERT INTO post (title, body, date) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $post, $datetime);

    // Set parameters and execute
    $title = $_POST['title'];
    $post = $_POST['post'];
    $datetime = date('Y-m-d H:i:s');

    if ($stmt->execute()) {
        echo "New post created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
