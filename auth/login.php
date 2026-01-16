<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Main Css -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- Outfit Font -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="centered">
    <div class="login-container">
        <form action="../includes/auth.php" method="POST" class="login-box">
            <h2>Login</h2>

            <!-- Shows error message if login fails -->
            <?php
            if (isset($_GET['error'])) {
                echo "<p class = 'error-msg'>Invalid username or password</p>";
            }

            if (isset($_GET['auth'])) { ?>
                <p class="error-msg">Please login to continue.</p>
            <?php }
            ?>

            <!-- Username -->
            <div style="position: relative;">
                <i data-lucide="user" class="i-field"></i>
                <input type="text" name="username" placeholder="Username" required">
            </div>

            <!-- Password -->
            <div style="position: relative;">
                <i data-lucide="lock" class="i-field"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <!-- Button -->
            <button type="submit" name="login">
                <i data-lucide="log-in"></i>Login
            </button>

            <!-- Register -->
            <p class="register-link">
                Don't have an account?
                <a href="register.php">Register</a>
            </p>
        </form>
    </div>

    <!-- Lucide Icons Activation -->
    <script>
        lucide.createIcons();
    </script>
</body>

</html>