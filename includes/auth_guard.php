<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php?auth=required");
    exit();
}

function isAdmin()
{
    // Allow admin by explicit is_admin flag or fallback to user id == 1
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) return true;
    return isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1;
}
