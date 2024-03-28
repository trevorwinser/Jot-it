<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
<?php
include 'navbar.php';
include 'verify-admin.php'; // Verify admin privileges

$message = $_GET['message'] ?? '';  // Clever way to avoid dealing with if(isset())
$user_id = $_GET['user_id'] ?? '';

$servername = "localhost";
$username = "61837175";
$password = "61837175";
$dbname = "db_61837175";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user exists
$stmt = $conn->prepare("SELECT id, username, enabled FROM User WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row['username'];
    $enabled = $row['enabled'];
} else {
    $message = 'User not found';
    $username = '';
    $enabled = 0;
}

$stmt->close();
$conn->close();
?>
<div>
    <form action="edit-user-submit.php" method="post">
        <h2>Editing User: <?php echo $user_id .' '. $username; ?></h2>
        <p id="message"><?php echo $message; ?></p>
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <input type="hidden" name="username" value="<?php echo $username; ?>">
        <label for="new_username">New Username:</label>
        <input type="text" id="new_username" name="new_username" value="<?php echo $username; ?>"><br><br>
        
        <input type="checkbox" id="enabled" name="enabled" <?php echo $enabled == 1 ? 'checked' : ''; ?>>
        <label for="enabled">Enabled</label><br><br>
        
        <input type="submit" value="Save">
    </form>
</div>


</body>
</html>
