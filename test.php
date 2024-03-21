<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jot-it";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

$result = $conn->query("SELECT p.* FROM post p LEFT JOIN comment c ON p.id = c.post_id GROUP BY p.id ORDER BY COUNT(c.id);");

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        // Print each column of the fetched row
        foreach ($row as $key => $value) {
            if ($key != 'image')
                echo "$key: $value <br>";
        }
        echo "<br>";
    }
} else {
    echo "0 results";
}
?>
