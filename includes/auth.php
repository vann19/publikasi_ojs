<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function checkAuth() {
    if (!isset($_SESSION['user_id'])) {
        // Hashed route name to use for redirect, you might need to adjust this
        header("Location: /login-secure-xyz.php");
        exit;
    }
}
