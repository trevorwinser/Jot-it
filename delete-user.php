<?php
include 'verify-admin.php';

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    include 'conn.php';

    $sql = "DELETE FROM user WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) { 
        header("Location: admin.php?delete_status=success");
        exit();
    } else {
        header("Location: admin.php?delete_status=error");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
