<?php
// fetch-category.php

// Assuming you have a database connection established
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "jot-it";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the category from the GET request
$category = $_GET['category'];

// Prepare and execute SQL query to select posts based on category
$sql = "SELECT * FROM posts WHERE category = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();

$posts = array();

// Fetch posts and store them in an array
while ($row = $result->fetch_assoc()) {
    $posts[] = array(
        'id' => $row['id'],
        'title' => $row['title'],
        'body' => $row['body'],
        'image' => $row['image'] // Assuming 'image' is a column in your 'posts' table
    );
}

// Return posts as JSON
header('Content-Type: application/json');
echo json_encode($posts);

$stmt->close();
$conn->close();
?>
