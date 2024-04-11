<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>

    <?php

    include 'navbar.php';
    include 'verify-login.php';

    // Initialize message
    $message = '';

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        // Retrieve current user details
        $username = $_SESSION['username'];
        $current_password = $_POST['current_password'];
        $new_username = $_POST['new_username'];
        $new_password = $_POST['new_password'];
        $image = $_FILES['image'];

        $stmt = $conn->prepare("SELECT password FROM user WHERE username = ?");
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
                if ($image['error'] == UPLOAD_ERR_OK) {
                    $check = getimagesize($image['tmp_name']);
                    if($check !== false) {
                        $fileType = $check['mime'];
                        if(in_array($fileType, ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'])) {
                            $imageData = file_get_contents($image['tmp_name']); 
                
                            $stmt = $conn->prepare("UPDATE user SET image = ? WHERE username = ?");
                            $null = NULL; 
                            $stmt->bind_param("bs", $null, $username);
                            $stmt->send_long_data(0, $imageData); 
                            if ($stmt->execute()) {
                                $message = 'Profile updated successfully';
                            } else {
                                $message = 'Failed to update image';
                            }
                            $stmt->close();
                        } else {
                            $message = 'Invalid image type. Only JPEG, PNG, and GIF are allowed.';
                        }
                    } else {
                        $message = 'File is not an image.';
                    }
                }
                
                if (!empty($new_password)) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE user SET password = ? WHERE username = ?");
                    $stmt->bind_param('ss', $hashed_password, $username);
                    $stmt->execute();
                    $message = $stmt->error ? 'Failed to update password' : 'Profile updated successfully';
                    $stmt->close();
                }

                if (!empty($new_username)) {
                    $stmt = $conn->prepare("UPDATE user SET username = ? WHERE username = ?");
                    $stmt->bind_param('ss', $new_username, $username);
                    $stmt->execute();
                    $message = $stmt->error ? 'Failed to update username' : 'Profile updated successfully';
                    $_SESSION['username'] = $new_username; 
                    $stmt->close();
                }
            }
        }
    }
    ?>

    <div>
    <form action="edit-profile.php" method="post" enctype="multipart/form-data">
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
