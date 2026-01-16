<?php
session_start();
include "db.php";
require "phpmailer/PHPMailer.php";
require "phpmailer/SMTP.php";
require "phpmailer/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;

/* Login */

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Allow login using either username or email
    $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if ($row['verified'] == 0) {
            header("Location: ../auth/login.php?verify=required");
            exit();
        }

        if (password_verify($password, $row['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            // mark admin if email matches site owner or known admin
            $_SESSION['is_admin'] = ($row['email'] === 'sunilkalla4444@gmail.com') ? 1 : 0;

            header("Location: ../pages/dashboard.php");
            exit();
        }
    }
    header("Location: ../auth/login.php?error=1");
    exit();
}

/* Register */
if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../auth/register.php?error=invalidemail");
        exit();
    }

    if (
        strlen($password) < 8 ||
        !preg_match('@[A-Z]@', $password) ||
        !preg_match('@[a-z]@', $password) ||
        !preg_match('@[0-9]@', $password) ||
        !preg_match('@[^\w]@', $password)
    ) {
        header("Location: ../auth/register.php?error=weakpassword");
        exit();
    }

    /* Check duplicate username */
    $checkUser = "SELECT id FROM users WHERE username = '$username' OR email='$email' LIMIT 1";
    $checkRun = mysqli_query($conn, $checkUser);

    if ($checkRun && mysqli_num_rows($checkRun) > 0) {
        header("Location: ../auth/register.php?error=userexists");
        exit();
    }

    /* Verification Code */
    $code = rand(100000, 999999);

    /* Hash Password */
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    /* Insert user with unverified status */
    $query = "INSERT INTO users (username, email, password, verification_code, verified)
    VALUES ('$username', '$email', '$hashedPassword', '$code', 0)";

    if (mysqli_query($conn, $query)) {

        /* Send verification email */
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = "Your_Host";
            $mail->SMTPAuth = true;
            $mail->Username = "Your_Email";
            $mail->Password = "Your_Password";
            $mail->SMTPSecure = "tls";
            $mail->Port = Your_Port;

            $mail->setFrom("sunilkalla4444@gmail.com", "PDF Summarizer");
            $mail->addAddress($email);
            $mail->Subject = "Verification Code";
            $mail->Body = "Welcome $username!\n\nYour verification code is: $code";

            $mail->send();
        } catch (Exception $e) {
            die("Error sending email: " . $mail->ErrorInfo);
        }

        /* Redirect to verification page */
        header("Location: ../auth/verify.php?email=$email");
        exit();
    } else {
        header("Location: ../auth/register.php?error=2");
        exit();
    }
}
