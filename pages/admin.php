<?php
/* session_start(); */
include "../includes/auth_guard.php";
include "../includes/db.php";

if (!isAdmin()) {
    header("Location: dashboard.php");
    exit();
}

/* Stats */
$userCount = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM users"))[0];
$pdfCount = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM pdf_files"))[0];
$summaryCount = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM summaries"))[0];

/* Users */
$users = mysqli_query($conn, "SELECT id, username, email, created_at FROM users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <!-- Main Css -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- Outfit Font -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="has-header has-footer admin-page">

    <?php include "../includes/header.php"; ?>

    <div class="page-container">
        <h2 class="page-title">Admin Panel</h2>

        <!-- Stats -->
        <div class="dashboard-grid">
            <div class="dash-box">
                <h3>Total Users</h3>
                <p><?php echo $userCount; ?></p>
            </div>
            <div class="dash-box">
                <h3>Total PDFs</h3>
                <p><?php echo $pdfCount; ?></p>
            </div>
            <div class="dash-box">
                <h3>Total Summaries</h3>
                <p><?php echo $summaryCount; ?></p>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Users List -->
        <h3 class="uploaded-title">Registered Users</h3>

        <div class="pdf-list-container">
            <?php while ($u = mysqli_fetch_assoc($users)): ?>
                <div class="pdf-item-card">
                    <div class="pdf-left">
                        <i data-lucide="user"></i>
                        <div class="pdf-info">
                            <p class="pdf-name"><?php echo htmlspecialchars($u['username']); ?></p>
                            <p class="pdf-date"><?php echo htmlspecialchars($u['email']); ?></p>
                        </div>
                    </div>

                    <?php if ($u['id'] != 1): ?>
                        <a href="../includes/delete_user.php?id=<?php echo $u['id']; ?>"
                            class="btn-view"
                            onclick="return confirm('Delete this user?')">
                            <i data-lucide="trash"></i> Delete
                        </a>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php
    include "../includes/footer.php";
    ?>

    <!-- Lucide Icons Activation -->
    <script>
        lucide.createIcons();
    </script>
</body>

</html>