<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jot-it";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST["username"], $_POST["password"]) && !empty($_POST["username"]) && !empty($_POST["password"])) {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    // Validate username and password
    if (!preg_match('/^[a-zA-Z0-9]{5,40}$/', $username) || strlen($password) < 8) {
        header("Location: register.php?message=Invalid input");
        exit();
    }

    // Check if username exists
    $stmt = $conn->prepare("SELECT * FROM User WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: register.php?message=Username has been taken");
        exit();
    }

    // Insert new user
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO User (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        header("Location: login.php?Account created. Try logging in");
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
