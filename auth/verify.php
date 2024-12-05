<?php
// Bắt đầu session
    session_start();

    if (!isset($_SESSION['username'])) {
        header("Location:http://localhost/webphp/auth/signin.php");
    }
?>