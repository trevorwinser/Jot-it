<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jot-it";

$conn = new mysqli($servername, $username, $password, $dbname);

if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        $title = $_POST['title'];
        $post = $_POST['post'];
        $category = $_POST['category'];
        $datetime = date('Y-m-d H:i:s');

        $sql = "INSERT INTO post (title, body, category, date) VALUES ('$title', '$post', '$category', '$datetime')";
    if ($conn->query($sql) === TRUE){
        echo "New post created successfully";
    }
    else{
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    }
?>