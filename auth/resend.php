<?php
include "../includes/db.php";
require "../includes/phpmailer/PHPMailer.php";
require "../includes/phpmailer/SMTP.php";
require "../includes/phpmailer/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;

$email = isset($_GET['email']) ? $_GET['email'] : '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: verify.php?error=invalidemail");
    exit();
}

$code = rand(100000, 999999);

/* Update new code (escaped) */
$safeEmail = mysqli_real_escape_string($conn, $email);
mysqli_query($conn, "UPDATE users SET verification_code='$code' WHERE email='$safeEmail' LIMIT 1");

/* Send email again */
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
    $mail->Subject = "New Verification Code";
    $mail->Body = "Your new verification code is: $code";

    $mail->send();
} catch (Exception $e) {
    header("Location: verify.php?email=" . urlencode($email) . "&error=sendfail");
    exit();
}

header("Location: verify.php?email=" . urlencode($email) . "&resent=1");
exit();
