<?php

$servername = "localhost";
$username = "61837175";
$password = "61837175";
$dbname = "db_61837175";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

?>