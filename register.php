<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/nav.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <table>
        <tr><th>Register</th></tr>
        <tr>
            <td>
                <?php if (isset($_GET['message'])) { echo '<p id="error">' . htmlspecialchars($_GET['message']) . '</p>'; } ?>
            </td>
        </tr>
        <form action="checkregister.php" method="POST">
        <tr>
            <td>
                <label for="username">Enter a Username:&nbsp;&nbsp;</label>
                <input type="text" id="username" name="username" pattern="[a-zA-Z0-9]{5,40}" title="Username should be 5-40 characters long and include only letters and numbers." required>
            </td>
        </tr>
        <tr>
            <td>
                <label for="password">Enter a Password:&nbsp;&nbsp;&nbsp;</label>
                <input type="password" id="password" name="password" minlength="8" required><br>
            </td>
        </tr>
        <tr>
            <td>
                <input type="submit" value="Submit">
            </td>
        </tr>
        <tr> 
            <td>
                <a href="login.php">Have an Account? Log In</a>
            </td>
        </tr>
        </form>
    </table>
</body>
</html>
