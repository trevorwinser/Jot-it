<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jot-it";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $title = trim($_POST['title']);
    $post = trim($_POST['post']);
    $datetime = date('Y-m-d H:i:s');
    $category = isset($_POST['category']) ? (int)$_POST['category'] : null; // Ensure $category is an integer

    if (isset($_SESSION['id'])) {
        $user_id = $_SESSION['id'];
    } else {
        echo "User not authenticated";
        exit;
    }

    if (strlen($title) > 100 || strlen($post) > 3000) {
        die("Title or post body exceeds allowed length.");
    }

    $image = NULL;
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $imageInfo = getimagesize($_FILES['image']['tmp_name']);
        if ($imageInfo === FALSE) {
            die("Uploaded file is not a valid image.");
        }

        if (!in_array($imageInfo['mime'], $allowedTypes)) {
            die("Uploaded image is not in an allowed format.");
        }

        $tmpName = $_FILES['image']['tmp_name'];
        $fp = fopen($tmpName, 'rb');
        $image = fread($fp, filesize($tmpName));
        fclose($fp);
    } 

    $stmt = $conn->prepare("INSERT INTO post (title, body, date, image, user_id, category) VALUES (?, ?, ?, ?, ?, ?)");
    $null = NULL;
    $stmt->bind_param("sssbii", $title, $post, $datetime, $null, $user_id, $category);
    $stmt->send_long_data(3, $image); 

    if ($stmt->execute()) {
        echo "New post created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?> 