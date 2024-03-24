<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <?php
    if (isset($_GET['delete_status'])) {
        if ($_GET['delete_status'] === "success") {
            echo "<script>showMessage('User deleted successfully.');</script>";
        } elseif ($_GET['delete_status'] === "error") {
            echo "<script>showMessage('Error deleting user.');</script>";
        }
    }
    ?>

    <form method='GET'>
        <select name="search_by">
            <option value="username">Username</option>
            <option value="email">Email</option>
            <option value="title">Post Title</option>
        </select>
        <input type='text' name='search' placeholder='Enter your search term'>
        <button type='submit'>Search</button>
    </form>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "jot-it";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_GET['search'])) {
        $search_query = '%'.$_GET['search'].'%';
        if (isset($_GET['search_by'])) {
            if ($_GET['search_by'] == 'title') {
                $sql = "SELECT DISTINCT user.* FROM user LEFT JOIN post ON user.id = post.user_id WHERE post.title LIKE ?";
            } else if ($_GET['search_by'] == 'email') {
                $sql = "SELECT * FROM user WHERE email LIKE ?";
            } else if ($_GET['search_by'] == 'username'){
                $sql = "SELECT * FROM user WHERE username LIKE ?";
            }
        }

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $search_query);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            die("Error executing query: " . $stmt->error);
        }
    } else {
        $sql = "SELECT * FROM user";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            die("Error executing query: " . $stmt->error);
        }
    }

    if ($result->num_rows > 0) {
        echo "<table><tr>";
        while ($fieldinfo = $result->fetch_field()) {
            echo "<th>".$fieldinfo->name."</th>";
        }
        echo "<th>Edit</th>";
        echo "<th>Delete</th>";
        echo "</tr>";

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
            echo "<td><a href='edit-user.php?user_id=".$row['id']."'>Edit</a></td>";
            echo "<td><a href='delete-user.php?user_id=".$row['id']."' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a></td>";
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
