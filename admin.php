<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <form method='GET'>
        <input type='text' name='search' placeholder='Search by username or post title' value=''>
        <button type='submit'>Search</button>
    </form>
    <?php
    include 'verify-admin.php';

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "jot-it";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Handle search query
    $search_query = "";
    if (isset($_GET['search'])) {
        $search_query = $_GET['search'];
        // Query to fetch rows based on search query
        $sql = "SELECT * FROM user WHERE username LIKE '%$search_query%' 
                UNION 
                SELECT user.* FROM user INNER JOIN post ON user.id = post.user_id WHERE post.title LIKE '%$search_query%'";
        $result = $conn->query($sql);
    } else {
        // Default query to fetch all rows
        $sql = "SELECT * FROM user";
        $result = $conn->query($sql);
    }

    // Display search results in a table
    if ($result->num_rows > 0) {
        // Output table with column names at the top
        echo "<table><tr>";
        while ($fieldinfo = $result->fetch_field()) {
            echo "<th>".$fieldinfo->name."</th>";
        }
        echo "</tr>";

        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>".$value."</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
    $conn->close();
    ?>
</body>
</html>
