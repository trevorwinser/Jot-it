<?php
include 'conn.php';

$sql = "SELECT id FROM post ORDER BY RAND() LIMIT 1";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $randomPostId = $row['id'];
    
    header("Location: postDetails.php?id=$randomPostId");
    exit();
} else {
    header("Location: home.php"); 
    exit();
}
$conn->close();
?>
