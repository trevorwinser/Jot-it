<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>

    <?php
        include 'navbar.php';
    ?>
    <?php
        include 'verify-login.php';
    ?>
    
    <div>
    <form action="profile.php" method="post" enctype="multipart/form-data">
    <h2>Update Profile</h2>
        <?php
            if(isset($_GET['message'])) {
                echo '<p id="message">' . $_GET['message'] . '</p>';
            }
        ?>
        <label for="image">Profile Image:</label><br>
        <input type="file" id="image" name="image"><br><br>

        <label for="new_username">New Username:</label><br>
        <input type="text" id="new_username" name="new_username"><br><br>

        <label for="current_password">Current Password:</label><br>
        <input type="password" id="current_password" name="current_password"><br><br>

        <label for="new_password">New Password:</label><br>
        <input type="password" id="new_password" name="new_password"><br><br>

        <input type="submit" value="Save">
    </form>
    </div>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "jot-it";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        // Retrieve current user details
        $username = $_SESSION['username'];

        $stmt = $conn->prepare("SELECT * FROM User WHERE username = ?");
        $stmt->bind_param('s', $username);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Verify current password
                $current_password = $_POST['current_password'];
                if (!password_verify($current_password, $row['password'])) {
                    header('Location: profile.php?message=Incorrect current password');
                    exit;
                }

                // If current password is correct, proceed with other updates
                $new_username = $_POST['new_username'];
                $new_password = $_POST['new_password'];
                $image = $_FILES['image'];

                // Upload new image
                if ($image['error'] == UPLOAD_ERR_OK) {
                    // Process uploaded image
                    $image_data = file_get_contents($image['tmp_name']);
                    // Update image in the database
                    $stmt = $conn->prepare("UPDATE User SET image = ? WHERE username = ?");
                    $stmt->bind_param('bs', $image_data, $username);
                    if (!$stmt->execute()) {
                        header('Location: profile.php?message=Failed to update image');
                        exit;
                    } else {
                        header('Location: profile.php?message=Profile updated successfully');
                    }
                }

                // Update password first so no need to check new username
                if (!empty($new_password)) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE User SET password = ? WHERE username = ?");
                    $stmt->bind_param('ss', $hashed_password, $username);
                    if (!$stmt->execute()) {
                        header('Location: profile.php?message=Failed to update password');
                        exit;
                    } else {
                        header('Location: profile.php?message=Profile updated successfully');
                    }
                }

                // Update username if provided
                if (!empty($new_username)) {
                    try {
                        $stmt = $conn->prepare("UPDATE User SET username = ? WHERE username = ?");
                        $stmt->bind_param('ss', $new_username, $username);
                        if (!$stmt->execute()) {
                            header('Location: profile.php?message=Failed to update username');
                            exit;
                        } else {
                            // Update session username
                            header('Location: profile.php?message=Profile updated successfully');
                            $_SESSION['username'] = $new_username;
                        }
                    } catch (mysqli_sql_exception $e) {
                        header('Location: profile.php?message=Username is taken');
                    }
                }
            } else {
                header('Location: profile.php?message=User not found');
            }
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    $conn->close();
    ?>

</body>
</html>
