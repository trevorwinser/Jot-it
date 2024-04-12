<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'conn.php';

if (isset($_GET['category']) && !empty($_GET['category'])) {
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $category = (int) $_GET['category'];
        $search_query = '%'.$_GET['search'].'%';
        $sql = "SELECT title, body, image, id, category FROM post WHERE category = ? AND (title LIKE ? OR body LIKE ?) ORDER BY date DESC LIMIT 10";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $category, $search_query, $search_query);
    } else {
        $category = (int) $_GET['category'];
        $sql = "SELECT title, body, image, id, category FROM post WHERE category = ? ORDER BY date DESC LIMIT 10";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", ($category));
    }
} else {
    if (isset($_GET['search']) && !empty($_GET['search'])) { 
        $search_query = '%'.$_GET['search'].'%';
        $sql = "SELECT title, body, image, id, category FROM post WHERE title LIKE ? OR body LIKE ? ORDER BY date DESC LIMIT 10";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $search_query, $search_query);
    } else {
        $sql = "SELECT title, body, image, id, category FROM post ORDER BY date DESC LIMIT 10";
        $stmt = $conn->prepare($sql);
    }
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