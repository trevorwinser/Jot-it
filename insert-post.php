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

    if (strlen($title) > 100 || strlen($post) > 1000) {
        die("Title or post body exceeds allowed length.");
    }

    $image = NULL;

    // Check if a file was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $imageInfo = getimagesize($_FILES['image']['tmp_name']);
        if ($imageInfo === FALSE) {
            die("Uploaded file is not a valid image.");
        }
        // Open the file and read its contents
        $tmpName = $_FILES['image']['tmp_name'];
        $fp = fopen($tmpName, 'rb'); // Read binary data
        $image = fread($fp, filesize($tmpName));
        fclose($fp);
    } else {
        $image = null; // No image uploaded
    }

    // Prepare statement with BLOB
    $stmt = $conn->prepare("INSERT INTO post (title, body, date, image, user_id) VALUES (?, ?, ?, ?, ?)");
    // bind_param doesn't work directly with BLOBs, use bind_param and send_long_data instead
    $null = NULL;
    $stmt->bind_param("sssbi", $title, $post, $datetime, $null, $user_id);
    $stmt->send_long_data(3, $image); // Send binary data

    if ($stmt->execute()) {
        echo "New post created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>
