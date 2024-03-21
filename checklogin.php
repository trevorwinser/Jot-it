<?php
session_start();

if(isset($_POST["username"]) && isset($_POST["password"]) && !empty($_POST["username"]) && !empty($_POST["password"])) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "jot-it";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    $stmt = $conn->prepare("SELECT password FROM User WHERE username = ?");
    $stmt->bind_param('s', $username);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $username;
                header("Location: home.php");
                exit();
            } else {
                header("Location: login.php?message=Password is incorrect");
                exit();
            }
        } else {
            header("Location: login.php?message=Username could not be found");
            exit();
        }
    } else {
        header("Location: login.php?message=Error: " . $stmt->error);
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: login.php?message=All fields are required");
    exit();
}
?>