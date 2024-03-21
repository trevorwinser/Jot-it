<?php
    session_start();
    unset($_SESSION["username"]);
    header("Location: http://localhost/Jot-It/home.php");
?>