<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['post_id']) && isset($_POST['commentText'])) {
        $postId = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
        $commentText = filter_input(INPUT_POST, 'commentText', FILTER_SANITIZE_STRING);
        if ($postId && $commentText !== false && $commentText !== "") {
                
        $servername = "localhost";
        $username = "61837175";
        $password = "61837175";
        $dbname = "db_61837175";

            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $userId = $_SESSION['id'];
            
            $dateTime = date('Y-m-d H:i:s');
            
            $stmt = $conn->prepare("INSERT INTO comment (post_id, commenter_id, body, date) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('iiss', $postId, $userId, $commentText, $dateTime);
            if ($stmt->execute()) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "error" => "Error inserting comment"]);
            }
            $stmt->close();
            $conn->close();
        } else {
            echo json_encode(["success" => false, "error" => "Invalid postId or empty commentText"]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "postId or commentText not set"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Form not submitted"]);
}
?>
