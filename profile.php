<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="css/profile.css">
    <style>
        .profile-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .profile-info {
            text-align: center;
        }
    </style>
</head>
<body>

    <?php
    include 'navbar.php';
    include 'verify-login.php';
    include 'conn.php';
    ?>
<form action="edit-profile.php" method="get">
            <h2>Profile</h2>
            <?php
            // Retrieve current user details
            $username = $_SESSION['username'];

            $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                echo '<p>User not found</p>';
            } else {
                $row = $result->fetch_assoc();
                echo '<p>Username: ' . htmlspecialchars($row['username']) . '</p>';
                echo '<p>Email: ' . htmlspecialchars($row['email']) . '</p>';
                // Display profile picture if available
                if (!empty($row['image'])) {
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '" alt="Profile Picture">';
                } else {
                    echo '<img src="images/profile-icon.png" alt="Profile Picture">';
                }
            }
            ?>
                <input type="submit" value="Edit Profile">
            </form>

</body>
</html>
