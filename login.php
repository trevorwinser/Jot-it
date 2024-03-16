<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jot-it";

$conn = new mysqli($servername, $username, $password, $dbname);


if(isset($_POST["username"]) && isset($_POST["password"])) {
    $sql = "SELECT * FROM User WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("sss", $username, $password);

    $username = $_POST["username"];
    $password = $_POST["password"];
    
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $stmt->error;
    }

    $stmt->close();
} else {
    echo "<p>All fields are required</p>";
    if (isset($_POST["username"]))
        echo "<p>Password is missing</p>";
    else
        echo "<p>Username is missing</p>";
}

$conn->close();
?>
