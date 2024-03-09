<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jot-it";

$conn = new mysqli($servername, $username, $password, $dbname);


if(isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["email"])) {
    $sql = "INSERT INTO User (username, password, email) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("sss", $username, $password, $email);

    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $stmt->error;
    }

    $stmt->close();
} else {
    echo "All fields are required";
}

$conn->close();
?>
