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
            <?php
            
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "jot-it";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            if(isset($_SESSION['username'])) {
                $username = $_SESSION['username'];

                $stmt = $conn->prepare("SELECT image FROM User WHERE username = ?");
                $stmt->bind_param('s', $username);

                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $profile_picture = $row['image']; // Assuming the column name for the image is 'image'
                        
                        // Display profile picture if available
                        if ($profile_picture) {
                            echo '<img src="data:image/jpeg;base64,' . base64_encode($profile_picture) . '" alt="Profile Picture">';
                        } else {
                            // Display default profile picture if no image is found
                            echo '<img src="images/profile-icon.png" alt="Profile Picture">';
                        }
                    }
                }
            }

            
            $conn->close();
            ?>
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

