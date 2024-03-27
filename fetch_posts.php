<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Start with the basic SQL query
$sql = "SELECT title, body, image, id, category FROM post";

// Initialize an array for the parameters
$params = [];

// Check if a category has been specified
if (isset($_GET['category']) && is_numeric($_GET['category'])) {
    $category = (int) $_GET['category']; // Ensure the category is an integer
    $sql .= " WHERE category = ?"; // Use a placeholder for the category
    $params[] = $category; // Add the category to the parameters array
}

$sql .= " ORDER BY date DESC LIMIT 5"; // Continue with the rest of the SQL query

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind parameters if necessary
if (!empty($params)) {
    $stmt->bind_param("i", ...$params); // 'i' indicates the parameters are integers
}

// Execute the statement
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
