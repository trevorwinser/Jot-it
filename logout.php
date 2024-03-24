<?php
    session_start();
    unset($_SESSION["username"]);
    unset($_SESSION['admin']);
    unset($_SESSION['id']);
    header("Location: home.php");
?>