<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/nav.css">
</head>
<body>
    <?php
        include 'navbar.php';
    ?>
    <table>
        <tr>
            <th>Log In</th>
        </tr>
        <tr>
            <td>
                <?php
                    if(isset($_GET['message'])) {
                        echo '<p id="message">' . $_GET['message'] . '</p>';
                    }
                ?>
            </td>
        </tr>
        <form action="checklogin.php" method="POST">
        <tr>
            <td>
                <label for="username">Username:&nbsp</label>
                <input type="text" id="username" name="username">
            </td>
        </tr>
        <tr>
            <td>
                <label for="password">Password:&nbsp&nbsp</label>
                <input type="password" id="password" name="password"><br>
            </td>
        </tr>
        <tr>
            <td>
                <input type="submit" value="Submit">
            </td>
        </tr>
        <tr> 
            <td>
                <a href="register.php">New Here? Create Account</a>
            </td>
        </tr>
        </form>
    </table>
</body>
</html>
