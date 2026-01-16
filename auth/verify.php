<?php
include "../includes/db.php";

$email = isset($_GET['email']) ? $_GET['email'] : "";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>

    <!-- Main Css -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- Outfit Font -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="centered">
    <div class="login-container">
        <form action="verify_process.php" method="POST" class="login-box">
            <h2>Email Verification</h2>

            <p>We send a 6-digit verification code to:
                <br>
                <strong>
                    <?php
                    echo htmlspecialchars($email);
                    ?>
                </strong>
            </p>

            <?php
            if (isset($_GET['error']) && $_GET['error'] == 1):
            ?>
                <p class="error-msg">Incorrect code. Try again.</p>
            <?php endif; ?>

            <div class="input-group">
                <i data-lucide="key-round"></i>
                <input type="text" name="code" placeholder="Enter 6-digit code" required maxlength="6">
            </div>

            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email) ?>">

            <button type="submit" name="verify">
                <i data-lucide="check-circle"></i> Verify Email
            </button>

            <p class="register-link">
                Didn't get the code?
                <a href="resent.php?email=<?php echo $email ?>">Resend Code</a>
            </p>
        </form>
    </div>

    <!-- Lucide Icons Activation -->
    <script>
        lucide.createIcons();
    </script>
</body>

</html>