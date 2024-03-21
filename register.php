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
    <header>
        <nav>
            <a href="launch-page.html">Home</a>
        </nav>
    </header>
    <table>
        <tr>
            <th>Register</th>
        </tr>
        <tr>
            <td>
                <?php
                if(isset($_GET['message'])) {
                    echo '<p id="error">' . $_GET['message'] . '</p>';
                }
                ?>
            </td>
        </tr>
        <form action="checkregister.php" method="POST">
        <tr>
            <td>
                <label for="fname">Enter a Username:&nbsp&nbsp</label>
                <input type="text" id="username" name="username">
            </td>
        </tr>
        <tr>
            <td>
                <label for="lname">Enter a Password:&nbsp&nbsp&nbsp</label>
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
                <a href="signin.html">Have an Account? Sign In</a>
            </td>
        </tr>
        </form>
    </table>
</body>
</html>
