<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jot-it";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT title, body, username FROM post ORDER BY date DESC LIMIT 5"; 
$result = $conn->query($sql); 
$posts = [];

if ($result && $result->num_rows > 0) { 
    while($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}
echo json_encode($posts);

$conn->close();
?>
