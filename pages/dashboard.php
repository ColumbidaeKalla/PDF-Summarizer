<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Summarizer</title>

    <!-- Main Css -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- Outfit Font -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="has-header has-footer">
    <?php
    include "../includes/auth_guard.php";
    include "../includes/header.php";
    ?>

    <div class="dashboard-container">

        <h2 class="dashboard-title">Welcome back, <?php echo $_SESSION['username']; ?> 👋</h2>

        <div class="dashboard-grid">
            <!-- Upload PDF -->
            <a href="upload.php" class="dash-box">
                <i data-lucide="upload"></i>
                <h3>Upload PDF</h3>
                <p>Upload and extract text from PDF files</p>
            </a>

            <!-- View PDF List -->
            <a href="pdf_list.php" class="dash-box">
                <i data-lucide="file-text"></i>
                <h3>Your PDFs</h3>
                <p>View uploaded PDF files.</p>
            </a>

            <!-- View Summaries -->
            <a href="summary_list.php" class="dash-box">
                <i data-lucide="book-open"></i>
                <h3>Summaries</h3>
                <p>Read generated summaries.</p>
            </a>

            <!-- Admin Panel -->
            <?php if (isAdmin()): ?>
                <a href="admin.php" class="dash-box">
                    <i data-lucide="shield-check"></i>
                    <h3>Admin Panel</h3>
                    <p>manage users and view logs.</p>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <?php include "../includes/footer.php"; ?>

    <!-- Lucide Icons Activation -->
    <script>
        lucide.createIcons();
    </script>
</body>

</html>