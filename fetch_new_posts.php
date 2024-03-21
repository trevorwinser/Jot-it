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

$sql = "SELECT title, body, image, id FROM post ORDER BY date DESC LIMIT 5";
$result = $conn->query($sql);
$posts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['image']) {
            // Convert binary data to base64
            $row['image'] = 'data:image/jpeg;base64,' . base64_encode($row['image']);
        }
        $posts[] = $row;
    }
}
echo json_encode($posts);


$conn->close();
?>
