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
    include 'verify-admin.php';
    include 'conn.php';
    include 'login-statistics.php';
    $stats = getLoginStats($conn); // Fetch login statistics

    // Check if $stats is set and is an array with all necessary keys.
    if (isset($stats) && is_array($stats) && isset($stats['today'], $stats['week'], $stats['month'])) {
        $loginsToday = $stats['today'];
        $loginsWeek = $stats['week'];
        $loginsMonth = $stats['month'];
    } else {
        // Handle the case where $stats is not set as expected.
        $loginsToday = $loginsWeek = $loginsMonth = "Statistics not available";
    }

    // Safely calculate max value to avoid division by zero
    $maxLogins = max($loginsToday, $loginsWeek, $loginsMonth);
    $maxLogins = ($maxLogins == 0) ? 1 : $maxLogins; // Prevent division by zero

   // Visualization example:
echo "<div style='margin-top: 20px;'>";
echo "<div style='margin-bottom: 20px;'>
    <strong>Unique Logins Today:</strong>
    <div style='width: 100%; background-color: #f1f1f1;'>
        <div style='width: " . ($loginsToday / max($loginsToday, $loginsWeek, $loginsMonth) * 100) . "%; background-color: #4CAF50; padding: 10px; color: white;'>" . $loginsToday . "</div>
    </div>
</div>";

echo "<div style='margin-bottom: 20px;'>
    <strong>Unique Logins This Week:</strong>
    <div style='width: 100%; background-color: #f1f1f1;'>
        <div style='width: " . ($loginsWeek / max($loginsToday, $loginsWeek, $loginsMonth) * 100) . "%; background-color: #2196F3; padding: 10px; color: white;'>" . $loginsWeek . "</div>
    </div>
</div>";

echo "<div style='margin-bottom: 20px;'>
    <strong>Unique Logins This Month:</strong>
    <div style='width: 100%; background-color: #f1f1f1;'>
        <div style='width: " . ($loginsMonth / max($loginsToday, $loginsWeek, $loginsMonth) * 100) . "%; background-color: #ff9800; padding: 10px; color: white;'>" . $loginsMonth . "</div>
    </div>
</div>";
echo "</div>";

?>

    <?php
    if (isset($_GET['delete_status'])) {
        if ($_GET['delete_status'] === "success") {
            echo "<script>alert('User deleted successfully.');</script>";
        } elseif ($_GET['delete_status'] === "error") {
            echo "<script>alert('Error deleting user.');</script>";
        }
    }
    ?>
    <h2>Users</h2>
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
                        echo '<td><img src="data:image/jpeg;base64,' . base64_encode($value) . '" alt="Profile Picture" class="user"></td>';
                    } else {
                        echo '<td><img src="images/profile-icon.png" alt="Profile Picture" class="user"></td>';
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

    $sql_comments = "SELECT * FROM comment";
    $stmt_comments = $conn->prepare($sql_comments);
    $stmt_comments->execute();
    $result_comments = $stmt_comments->get_result();

    if (!$result_comments) {
        die("Error executing query: " . $stmt_comments->error);
    }

    echo "<h2>Comments</h2>";
    if ($result_comments->num_rows > 0) {
        echo "<table><tr>";
        while ($fieldinfo_comments = $result_comments->fetch_field()) {
            echo "<th>".$fieldinfo_comments->name."</th>";
        }
        echo "<th>Edit</th>";
        echo "<th>Delete</th>";
        echo "</tr>";

        while($row_comments = $result_comments->fetch_assoc()) {
            echo "<tr>";
            foreach ($row_comments as $key_comments => $value_comments) {
                echo "<td>".$value_comments."</td>";
            }
            echo "<td><a href='edit-comment.php?comment_id=".$row_comments['id']."'>Edit</a></td>";
            echo "<td><a href='delete-comment.php?comment_id=".$row_comments['id']."' onclick='return confirm(\"Are you sure you want to delete this comment?\")'>Delete</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No comments found";
    }

    $sql_posts = "SELECT * FROM post";
    $stmt_posts = $conn->prepare($sql_posts);
    $stmt_posts->execute();
    $result_posts = $stmt_posts->get_result();

    if (!$result_posts) {
        die("Error executing query: " . $stmt_posts->error);
    }

    echo "<h2>Posts</h2>";
    if ($result_posts->num_rows > 0) {
        echo "<table><tr>";
        while ($fieldinfo_posts = $result_posts->fetch_field()) {
            echo "<th>".$fieldinfo_posts->name."</th>";
        }
        echo "<th>Edit</th>";
        echo "<th>Delete</th>";
        echo "</tr>";

        while($row_posts = $result_posts->fetch_assoc()) {
            echo "<tr>";
            foreach ($row_posts as $key_posts => $value_posts) {
                if ($key_posts === 'image') {
                    if ($value_posts) {
                        echo '<td><img src="data:image/jpeg;base64,' . base64_encode($value_posts) . '" alt="Post Image" class="post"></td>';
                    } else {
                        echo '<td>No Image</td>';
                    }
                } else {
                    echo "<td>".$value_posts."</td>";
                }
            }
            echo "<td><a href='edit-post.php?post_id=".$row_posts['id']."'>Edit</a></td>";
            echo "<td><a href='delete-post.php?post_id=".$row_posts['id']."' onclick='return confirm(\"Are you sure you want to delete this post?\")'>Delete</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No comments found";
    }

    $conn->close();
    ?>
</body>

</html>
