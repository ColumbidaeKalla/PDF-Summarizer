<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
            <h2>Create Account</h2>

            <!-- Error if username exists -->
            <?php
            if (isset($_GET['error']) && $_GET['error'] == "userexists") {
                echo "<p class='error-msg'>Username already taken</p>";
            }
            ?>

            <!-- Username -->
            <div>
                <i data-lucide="user-plus"></i>
                <input type="text" name="username" placeholder="Choose a username" required>
            </div>

            <!-- Email -->
            <div>
                <i data-lucide="mail"></i>
                <input type="email" name="email" placeholder="Your email" required>
            </div>

            <!-- Password -->
            <div>
                <i data-lucide="lock"></i>
                <input type="password"
                    name="password"
                    required
                    placeholder="Create Strong Password"
                    pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}"
                    title="Password must be at least 8 character, include uppercase, number, and special character.">
            </div>

            <!-- Register Button -->
            <button type="submit" name="register">
                <i data-lucide="user-check"></i>Register
            </button>

            <p class="register-link">
                Already have an account?
                <a href="login.php">Login</a>
            </p>
        </form>
    </div>

    <!-- Lucide Icons Activation -->
    <script>
        lucide.createIcons();
    </script>
</body>

</html>