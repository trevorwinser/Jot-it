<?php
session_start();
$servername = "localhost";
$username = "61837175";
$password = "61837175";
$dbname = "db_61837175";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST["username"], $_POST["password"], $_POST["email"]) && !empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["email"])) {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $email = $_POST["email"];

    if (!preg_match('/^[a-zA-Z0-9]{5,40}$/', $username) || strlen($password) < 8 || strlen($password) > 60 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: register.php?message=Invalid input");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: register.php?message=Username has been taken");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO user (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $email);

    if ($stmt->execute()) {
        header("Location: login.php?Account created. Try logging in");
        exit();
    } else {
        header("Location: register.php?message=Error: " . $stmt->error);
        exit();
    }

} else {
    header("Location: register.php?message=All fields are required");
    exit();
}

?>
