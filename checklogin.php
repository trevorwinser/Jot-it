<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if(isset($_POST["username"]) && isset($_POST["password"]) && !empty($_POST["username"]) && !empty($_POST["password"])) {
    $servername = "localhost";
    $username = "61837175";
    $password = "61837175";
    $dbname = "db_61837175";

    $conn = new mysqli($servername, $username, $password, $dbname);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        
    }
    
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    $stmt = $conn->prepare("SELECT password, admin, id, enabled, image FROM user WHERE username = ?");
    $stmt->bind_param('s', $username);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                if ($row['enabled'] == 1) {
                    $_SESSION['username'] = $username;
                    $_SESSION['admin'] = $row['admin'];
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['profile_picture'] = $row['image'];
                    header("Location: home.php");
                    exit();
                } else {
                    header("Location: login.php?message=Account has been disabled");
                }
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
} else {
    header("Location: login.php?message=All fields are required");
    exit();
}
?>