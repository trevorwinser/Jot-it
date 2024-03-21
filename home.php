<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    <?php
    include 'navbar.php';
    ?>
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
        const postboardImg = document.getElementById('postboardImg');
        postboard.innerHTML = ''; // Clear existing posts 
        postboardImg.innerHTML = ''; // Also clear existing posts in the image board

        let hasPosts = false; // Flag to check if there are any posts
        let hasImagePosts = false; // Separate flag for image posts

        posts.forEach(post => {
            hasPosts = true; // There's at least one post
            const postDiv = document.createElement('div');
            postDiv.className = 'post';
            let postContent = `<h2>${post.title}</h2><p>${post.body}</p>`;
            if (post.image) {
                hasImagePosts = true; // There's at least one image post
                postContent += `<img src="${post.image}" alt="Post image" style="max-width:100%;">`;
                postDiv.innerHTML = postContent;
                postboardImg.appendChild(postDiv); // Append to image board
            } else {
                postDiv.innerHTML = postContent;
                postboard.appendChild(postDiv); // Append to regular board
            }
        });

        // Set visibility based on whether there are posts
        postboard.style.display = hasPosts ? 'block' : 'none';
        postboardImg.style.display = hasImagePosts ? 'block' : 'none'; // Set display based on image posts
    })
    .catch(error => console.error('Error fetching new posts:', error));
}

// Initial call and setup for refreshing posts
fetchNewPosts();
setInterval(fetchNewPosts, 10000);


</script>
</body>
</html>

