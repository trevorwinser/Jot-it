<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/create-post.css">
    <link rel="stylesheet" href="css/font.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
</head>

<body>
    <?php
        include 'navbar.php';
        include 'verify-login.php';

    ?>
    <div class="group-form">
        <form id="createPostForm" method="post" enctype="multipart/form-data">

            <div>
                <label for="title">Title</label><br>
                <input type="text" name="title" class="form-control" id="title" maxlength="100" required>  
            </div>
            <br><br>
           
            <div>
                <label for="image">Image</label><br>
                <input type="file" name="image" class="form-control" id="image">
            </div>
            <br><br>

            <div>
                <label for="post">Text</label><br>
                <textarea rows="5" class="form-control" name="post" id="post" maxlength="3000" required></textarea>
            </div>
            <div>
                <button id="toggleCategory" type="button">Toggle Category</button>
                <div id="categoryDropdown" style="display: none;">
                <label for="category">Category</label><br>
                <select name="category" required>
                    <option value="0">Select category</option>
                    <option value="1">Art</option>
                    <option value="2">Food</option>
                    <option value="3">Sports</option>
                    <option value="4">Travel</option>
                </select>
                </div>
            </div>
            <br>
            <button type="submit">Submit</button>
            <button type="reset">Reset</button>
            <br><br>
        </form>
    </div>
    <script>
        document.getElementById('toggleCategory').addEventListener('click', function() {
            var categoryDropdown = document.getElementById('categoryDropdown');
            categoryDropdown.style.display = (categoryDropdown.style.display === 'none') ? 'block' : 'none';
        });

        document.getElementById('createPostForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
    
            const formData = new FormData(this);
    
            fetch('insert-post.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(result => {
                alert("New post created successfully");
                console.log(result);
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>