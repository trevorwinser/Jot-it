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
    include 'verify-login.php';

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "jot-it";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Initialize message
    $message = '';

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        // Retrieve current user details
        $username = $_SESSION['username'];
        $current_password = $_POST['current_password'];
        $new_username = $_POST['new_username'];
        $new_password = $_POST['new_password'];
        $image = $_FILES['image'];

        // Check if current password is correct
        $stmt = $conn->prepare("SELECT password FROM User WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows === 0) {
            $message = 'User not found';
        } else {
            $row = $result->fetch_assoc();
            if (!password_verify($current_password, $row['password'])) {
                $message = 'Incorrect current password';
            } else {
                // Proceed with updates if current password is correct
                // Update the image if a new one has been uploaded
                if ($image['error'] == UPLOAD_ERR_OK) {
                    $imageData = file_get_contents($image['tmp_name']); // Get binary data
                    $stmt = $conn->prepare("UPDATE User SET image = ? WHERE username = ?");
                    $null = NULL; // Placeholder for blob data
                    $stmt->bind_param("bs", $null, $username);
                    $stmt->send_long_data(0, $imageData); // Send binary data
                    $stmt->execute();
                    $message = $stmt->error ? 'Failed to update image' : 'Profile updated successfully';
                    $stmt->close();
                }

                // Update password if provided
                if (!empty($new_password)) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE User SET password = ? WHERE username = ?");
                    $stmt->bind_param('ss', $hashed_password, $username);
                    $stmt->execute();
                    $message = $stmt->error ? 'Failed to update password' : 'Profile updated successfully';
                    $stmt->close();
                }

                // Update username if provided
                if (!empty($new_username)) {
                    $stmt = $conn->prepare("UPDATE User SET username = ? WHERE username = ?");
                    $stmt->bind_param('ss', $new_username, $username);
                    $stmt->execute();
                    $message = $stmt->error ? 'Failed to update username' : 'Profile updated successfully';
                    $_SESSION['username'] = $new_username; // Update session username
                    $stmt->close();
                }
            }
        }
    }
    ?>

    <div>
    <form action="profile.php" method="post" enctype="multipart/form-data">
        <h2>Update Profile</h2>
        <p id="message"><?php echo $message; ?></p>
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

</body>
</html>
