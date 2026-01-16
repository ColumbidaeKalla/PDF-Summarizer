<?php
session_start();
include "auth_guard.php";
include "db.php";

if (!isAdmin() || !isset($_GET['id'])) {
    header("Location: ../pages/admin.php");
    exit();
}

$id = (int) $_GET['id'];

if ($id != 1) {
    mysqli_query($conn, "DELETE FROM users WHERE id = $id");
}

header("Location: ../pages/admin.php");
exit();
