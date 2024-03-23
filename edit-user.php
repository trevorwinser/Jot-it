<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>
<body>
    <?php
    include 'navbar.php';

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

    // Check if user_id is provided in the URL
    if (isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];

        // Query to fetch user based on user_id
        $sql = "SELECT * FROM user WHERE id = $user_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // User found, display form to edit user details
            $user = $result->fetch_assoc();
            ?>

            <h2>Edit User</h2>
            <form method="POST" action="update-username.php" class="user-form">
                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

                <div class="form-group">
                    <label for="username">New Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update Username</button>
                </div>
            </form>



            <?php
        } else {
            echo "User not found.";
        }
    } else {
        echo "User ID not provided.";
    }

    $conn->close();
    ?>
</body>
</html>
