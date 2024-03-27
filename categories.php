<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    <?php
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $category = isset($_POST['category']) ?? 0;
            $servername = "localhost";
            $username_db = "root";
            $password_db = "";
            $dbname = "jot-it";
        
            $conn = new mysqli($servername, $username_db, $password_db, $dbname);
        
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }



        } else {
            echo "<p>Category not specified in header.</p>";
        }
    ?>

</body>
</html>

