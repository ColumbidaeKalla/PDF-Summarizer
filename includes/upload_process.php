<?php
session_start();
include "db.php";

if (isset($_POST['upload'])) {
    $user_id = $_SESSION['user_id'];

    if (!isset($_FILES['pdf_file']) || $_FILES['pdf_file']['error'] !== 0) {
        header("Location: ../pages/upload.php?error=1");
        exit();
    }

    $file = $_FILES['pdf_file'];

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if ($mime !== 'application/pdf') {
        header("Location: ../pages/upload.php?error=invalid");
        exit();
    }

    $original_name = mysqli_real_escape_string($conn, $file['name']);
    $new_filename = time() . "_" . rand(1000, 9999) . ".pdf";

    $upload_path = "../uploads/pdf/" . $new_filename;

    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        $sql = "INSERT INTO pdf_files (user_id, filename, original_name)
                VALUES ($user_id, '$new_filename', '$original_name')";

        if (mysqli_query($conn, $sql)) {
            header("Location: ../pages/upload.php?success=1");
            exit();
        }
    }
    header("Location: ../pages/upload.php?error=1");
    exit();
}
