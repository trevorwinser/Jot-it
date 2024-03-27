<?php

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
    if(isset($_SESSION['id'])) {
        $user_id = $_SESSION['id'];
    }else {
        echo "User not authenticated";
        echo  $_SESSION['id'];
        exit;
    }
    $category = trim($_POST['category']);

    if (strlen($title) > 100 || strlen($post) > 3000) {
        die("Title or post body exceeds allowed length.");
    }

    $image = NULL;

    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $imageInfo = getimagesize($_FILES['image']['tmp_name']);
        if ($imageInfo === FALSE) {
            die("Uploaded file is not a valid image.");
        }

        $tmpName = $_FILES['image']['tmp_name'];
        $fp = fopen($tmpName, 'rb');
        $image = fread($fp, filesize($tmpName));
        fclose($fp);
    } else {
        $image = null; 
    }

    $stmt = $conn->prepare("INSERT INTO post (title, body, date, image, user_id,category) VALUES (?, ?, ?, ?, ?,?)");
    $null = NULL;
    $stmt->bind_param("sssbis", $title, $post, $datetime, $null, $user_id, $category);
    $stmt->send_long_data(3, $image); 

    if ($stmt->execute()) {
        echo "New post created successfully";
        echo "<pre>";
print_r($_FILES['eventImage']);
echo "</pre>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>
