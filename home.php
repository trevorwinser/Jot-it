<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/nav.css">
</head>
<body>
    <header>
        <nav>

            <a href="launch-page.html">Home</a>
            <a href="create-post.html">Create Post</a>
            <div class="right-side">
            <?php 
                session_start();
                if(isset($_SESSION['username'])) {
                    echo '<span class="signed-in">Signed in as: ' . $_SESSION['username'] . '</span>';
                    echo '<a href="logout.php">Log Out</a>';
                    echo '<a href="profile.html">Profile</a>';
                } else {
                    echo '<a href="login.php">Log In</a>';
                }
            ?>
            </div>
        </nav>
    </header>

    <div class="sidebar">
        <div class="profile-section">
                <img src="images/profile-icon.png" alt="Profile"> </a>
                <p>Username:</p>
                <p>Pronouns:</p>
            
        </div>
        <div class="bookmarks-container">
            <div class="bookmark" id="bookmark1">
                <a href="#">
                    <p>Bookmark 1</p>
                </a>
                <img src="images/bookmark1.png" alt="Bookmark 1">
            </div>
            <div class="bookmark" id="bookmark2">
                <a href="#">
                    <p>Bookmark 2</p>
                </a>
                <img src="images/bookmark2.png" alt="Bookmark 2">
            </div>
            <div class="bookmark" id="bookmark3">
                <a href="#">
                    <p>Bookmark 3</p>
                </a>
                <img src="images/bookmark3.png" alt="Bookmark 3">
            </div>
            <div class="bookmark" id="bookmark4">
                <a href="#">
                    <p>Bookmark 4</p>
                </a>
                <img src="images/bookmark4.png" alt="Bookmark 4">
            </div>
        </div>
    </div>

    <!-- Main content -->
    <main>
        <div id="postboardContainer">
            <div id="postboard">
                <div class="post">
                    
                </div>
                <div class="post">
                    
                </div>
                <div class="post">
                    
                </div>
                <div class="post">
                    
                </div>
            </div>
            <div id="postboardImg">
                <div class="post">
                    
                </div>
                <div class="post">
                     
                </div>
                <div class="post">
                   
                </div>
                <div class="post">
                    
                </div>
            </div>
        </div>
    </main>
    <script>
    // Function to fetch new posts and update the homepage
    function fetchNewPosts() {
        fetch('fetch_new_posts.php') 
        .then(response => response.json())
        .then(posts => {
            const postboard = document.getElementById('postboard');
            postboard.innerHTML = ''; // Clear existing posts 
            posts.forEach(post => {
                const postDiv = document.createElement('div');
                postDiv.className = 'post';
                postDiv.innerHTML = `<h2>${post.title}</h2><p>${post.body}</p>`;
                postboard.appendChild(postDiv);
            });
        })
        .catch(error => console.error('Error fetching new posts:', error));
    }
    // Fetch new posts every 30 seconds 
    setInterval(fetchNewPosts, 30000);
    window.addEventListener('new-post', fetchNewPosts);
    </script>
</body>
</html>
