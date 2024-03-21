
<link rel="stylesheet" href="css/nav.css">
<link rel="stylesheet" href="css/font.css">

<header>
        <nav>

            <a href="home.php">Home</a>
            <a href="create-post.php">Create Post</a>
            <div class="right-side">
            <?php 
                session_start();
                if(isset($_SESSION['username'])) {
                    echo '<span class="signed-in">Signed in as: ' . $_SESSION['username'] . '</span>';
                    echo '<a href="logout.php">Log Out</a>';
                    echo '<a href="profile.html">Profile</a>';
                } else {
                    echo '<a href="login.php">Log In</a>';
                    echo '<a href="register.php">Register</a>';
                }
            ?>
            </div>
        </nav>
    </header>
