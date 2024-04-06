<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jot-it";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?;");
    $stmt->bind_param('s', $username);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
    } else {
        header("Location: login.php?message=Log in required"); // Change 
        exit();
    }
} else {
    header("Location: login.php?message=Log in required");
    exit();
}
?>
