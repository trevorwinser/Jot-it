<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <?php
        include 'navbar.php';
    ?>
    <form method='GET'>
        <select name="search_by">
            <option value="username">Username</option>
            <option value="title">Title</option>
        </select>
        <input type='text' name='search' placeholder='Enter your search term'>
        <button type='submit'>Search</button>
    </form>
    <?php
    
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
    $search_by = "username"; // Default search by username
    if (isset($_GET['search'])) {
        $search_query = $_GET['search'];
        if (isset($_GET['search_by']) && ($_GET['search_by'] == 'title')) {
            $search_by = "title";
        }
        // Query to fetch rows based on search query
        if ($search_by == "username") {
            $sql = "SELECT * FROM user WHERE username LIKE '%$search_query%'";
        } else {
            $sql = "SELECT user.*, post.image AS profile_picture FROM user LEFT JOIN post ON user.id = post.user_id WHERE post.title LIKE '%$search_query%'";
        }
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
        echo "<th>Edit</th>"; // Additional column for edit link
        echo "</tr>";

        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $key => $value) {
                if ($key === 'image') {
                    if ($value) {
                        echo '<td><img src="data:image/jpeg;base64,' . base64_encode($value) . '" alt="Profile Picture"></td>';
                    } else {
                        echo '<td><img src="images/profile-icon.png" alt="Profile Picture"></td>';
                    }
                } else {
                    echo "<td>".$value."</td>";
                }
            }
            echo "<td><a href='edit-user.php?user_id=".$row['id']."'>Edit</a></td>"; // Edit link
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
