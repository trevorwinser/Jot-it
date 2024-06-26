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
                    if (isset($_SESSION['profile_picture']) && !empty($_SESSION['profile_picture'])) {
                        $profile_picture = $_SESSION['profile_picture'];
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($profile_picture) . '" alt="Profile Picture" id="profile-picture">';
                    } else {
                        echo '<img src="images/profile-icon.png" alt="Profile Picture" id="profile-picture">';
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
            <div class="search-container">
                <form id="searchForm" onsubmit="handleSearch(event)">
                    <input type="text" placeholder="Search posts..." name="search" id="searchInput">
                    <button type="submit">Search</button>
                </form>
            </div>
        </div>

        <div id="refreshTimer"></div>
        <div id="postboardContainer">
            <div id="postboard"></div>
            <div id="postboardImg"></div>
        </div>
    </main>
</body>
<script>
const countdownElement = document.getElementById('refreshTimer');
let countdownInterval;

function startCountdown() {
    let timeLeft = 10;

    countdownInterval = setInterval(() => {
        timeLeft--;
        if (timeLeft < 0) {
            clearInterval(countdownInterval);
            fetchNewPosts();
            startCountdown();
        } else {
            updateCountdownDisplay(timeLeft);
        }
    }, 1000);
}

function updateCountdownDisplay(seconds) {
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = seconds % 60;
    const formattedTime = `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
    countdownElement.textContent = `Next update in ${formattedTime}`;
}

function handleSearch(event) {
    event.preventDefault(); // Prevent the form from submitting normally
    const searchInput = document.getElementById('searchInput').value; // Get the value of the search input
    fetchNewPosts(searchInput); // Call fetchNewPosts function with search value
}

// Fetches posts and formats them to display
function fetchNewPosts(search = '') {
    const urlParams = new URLSearchParams(window.location.search);
    const category = urlParams.get('category'); // Get the category value from the URL

    fetch('fetch-posts.php?category=' + category + '&search=' + search)
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
                let postContent = `<h2 style="display:flex;">${post.title}</h2><p>${post.body}</p><span class="likes">Likes: ${post.likes}`
                if (post.likes > 2) {
                    postContent += "🔥";
                }
                postContent += "</span>";
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

// Call fetchNewPosts function when the page loads
document.addEventListener('DOMContentLoaded', () => {
    fetchNewPosts();
    startCountdown();
});

// Call fetchNewPosts function when the category or search query changes
window.addEventListener('popstate', () => {
    fetchNewPosts();
});

// Call fetchNewPosts function when search form is submitted
const searchForm = document.getElementById('searchForm');
searchForm.addEventListener('submit', handleSearch);
</script>


</html>
