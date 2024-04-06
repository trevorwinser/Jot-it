<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jot-it";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['category'])) {
    $category = (int) $_GET['category'];
    $sql = "SELECT title, body, image, id, category FROM post WHERE category = ? ORDER BY date DESC LIMIT 5";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", ($category));
} else {
    $sql = "SELECT title, body, image, id, category FROM post ORDER BY date DESC LIMIT 5";
    $stmt = $conn->prepare($sql);
}


$stmt->execute();
// Get the result
$result = $stmt->get_result();
$posts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['image']) {
            // Convert binary data to base64 for the frontend to display
            $row['image'] = 'data:image/jpeg;base64,' . base64_encode($row['image']);
        }
        $posts[] = $row;
    }
}

echo json_encode($posts); // Output the posts as JSON

$stmt->close();
$conn->close();
?>