<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jot-it";

$conn = new mysqli($servername, $username, $password, $dbname);


if(isset($_POST["username"]) && isset($_POST["password"]) && !empty($_POST["username"]) && !empty($_POST["password"])) {
    $sql = "SELECT * FROM User WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param('ss', $username, $password);

    $username = $_POST["username"];
    $password = $_POST["password"];
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            if (strcmp($row['username'], $username) == 0 && strcmp($row['password'], password_hash($password, PASSWORD_DEFAULT)) == 0) {
                session_start();
                $_SESSION['username'] = $username;      // Can access across pages.
            } else {
                if (strcmp($row['username'], $username) == 0) {
                    echo "<p>Username could not be found</p>";
                } else {
                    echo "<p>Password is incorrect"
                }
            }
        }
    } else {
        echo "Error: " . $sql . "<br>" . $stmt->error;
    }

    $stmt->close();
} else {
    echo "<p>All fields are required</p>";
    if (isset($_POST["username"]) || !empty($_POST["username"]))
        echo "<p>Password is missing</p>";
    else
        echo "<p>Username is missing</p>";
}

$conn->close();
?>
