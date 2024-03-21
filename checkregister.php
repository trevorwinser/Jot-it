<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jot-it";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST["username"]) && isset($_POST["password"]) && !empty($_POST["username"]) && !empty($_POST["password"])) {
    $stmt = $conn->prepare("SELECT * FROM User WHERE username = ?");
    $username = $_POST["username"];
    $stmt->bind_param("s", $username);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            header("Location: register.php?message=Username has been taken");
        }
    }

    $stmt = $conn->prepare("INSERT INTO User (username, password) VALUES (?, ?)");
    
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        session_start();
        $_SESSION['username'] = $username;
        header("Location: home.php");
        exit();
    } else {
        header("Location: register.php?message=Error: " . $stmt->error);
        exit();
    }

    $stmt->close();
} else {
    header("Location: register.php?message=All fields are required");
    exit();
}

$conn->close();
?>
