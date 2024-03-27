<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    
    
    <main>
    <div class="sidebar">
        <!-- Profile Section -->
        <div class="profile-section">
            <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "jot-it";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                if (isset($_SESSION['username'])) {
                    $username = $_SESSION['username'];
                    $stmt = $conn->prepare("SELECT image FROM User WHERE username = ?");
                    $stmt->bind_param('s', $username);

                    if ($stmt->execute()) {
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $profile_picture = $row['image'];

                            if ($profile_picture) {
                                echo '<img src="data:image/jpeg;base64,' . base64_encode($profile_picture) . '" alt="Profile Picture" id="profile-picture">';
                            } else {
                                echo '<img src="images/profile-icon.png" alt="Profile Picture" id="profile-picture">';
                            }
                        }
                    }
                    $conn->close();
                }
            ?>
        </div>
        <!-- End of Profile Section -->

        <!-- Bookmarks Container -->
        <div class="bookmarks-container">
            <div class="bookmark" id="bookmark1">
                <img src="images/bookmark1.png" alt="Bookmark 1">
                <p><a href="categories.php?category=1">Art</a></p>
            </div>
            <div class="bookmark" id="bookmark2">
                <img src="images/bookmark2.png" alt="Bookmark 2">
                <p><a href="categories.php?category=2">Food</a></p>
            </div>
            <div class="bookmark" id="bookmark3">
                <img src="images/bookmark3.png" alt="Bookmark 3">
                <p><a href="categories.php?category=3">Sports</a></p>
            </div>
            <div class="bookmark" id="bookmark4">
                <img src="images/bookmark4.png" alt="Bookmark 4">
                <p><a href="categories.php?category=4">Travel</a></p>
            </div>
        </div>
        <!-- End of Bookmarks Container -->
    </div>
        <div id="postboardContainer">
            <div id="postboard"></div>
            <div id="postboardImg"></div>
        </div>
    </main>

</body>

    <script>
    // Fetches posts and formats them to display
    function fetchNewPosts() {
    fetch('fetch-posts.php')
    .then(response => response.json())
    .then(posts => {
        const postboard = document.getElementById('postboard');
        const postboardImg = document.getElementById('postboardImg');
        postboard.innerHTML = ''; 
        postboardImg.innerHTML = ''; 
        let hasPosts = false; 
        let hasImagePosts = false; 

        posts.forEach(post => {
            hasPosts = true; 
            const postDiv = document.createElement('div');
            postDiv.className = 'post';
            let postContent = `<h2>${post.title}</h2><p>${post.body}</p>`;
            if (post.image) {
                hasImagePosts = true; 
                postContent += `<img src="${post.image}" alt="Post image" style="max-width:100%;">`;
                postDiv.innerHTML = postContent;
                postDiv.addEventListener('click', () => {window.location.href = 'postDetails.php?id=' + post.id;});
                postboardImg.appendChild(postDiv); 
            } else {
                postDiv.innerHTML = postContent;
                postDiv.addEventListener('click', () =>   {window.location.href = 'postDetails.php?id=' + post.id;});
                postboard.appendChild(postDiv); 
            }
        });

        postboard.style.display = hasPosts ? 'block' : 'none';
        postboardImg.style.display = hasImagePosts ? 'block' : 'none';
    })
    .catch(error => console.error('Error fetching new posts:', error));
}

fetchNewPosts();
setInterval(fetchNewPosts, 10000);


</script>
</body>
</html>

