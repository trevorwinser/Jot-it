<?php
$servername = "localhost";
$username = "61837175";
$password = "61837175";
$dbname = "db_61837175";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// $sql = "INSERT INTO User
// VALUES ('Joe', 'Mama', 'joemama@gmail.com', 'None', 'TRUE')";

// if ($conn->query($sql) === TRUE) {
//   echo "New record created successfully";
// } else {
//   echo "Error: " . $sql . "<br>" . $conn->error;
// }

$sql = "SELECT username, password FROM user";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "id: " . $row["username"]. " - Name: " . $row["password"];
  }
} else {
  echo "0 results";
}

$conn->close();
?>
