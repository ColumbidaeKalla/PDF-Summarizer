<?php
include "../includes/db.php";

if (isset($_POST['verify'])) {
    $email = $_POST['email'];
    $code = $_POST['code'];

    $sql = "SELECT * FROM users WHERE email='$email' AND verification_code='$code' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) === 1) {

        /* Update user as verified */
        $update = "UPDATE users SET verified=1, verification_code=NULL WHERE email='$email'";
        mysqli_query($conn, $update);

        header("Location: login.php?verified=1");
        exit();
    }

    /* Wrong code */
    header("Location: verify.php?email=$email&error=1");
    exit();
}

?>