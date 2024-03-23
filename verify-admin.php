<?php
if(isset($_SESSION['admin'])) {
    if ($_SESSION['admin'] != 1) {
        header("Location: home.php");
        exit();
    }
} else {
    header("Location: home.php");
    exit();
}
?>