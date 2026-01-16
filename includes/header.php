<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="site-header">

    <a class="brand" href="../pages/dashboard.php">PDF Summarizer</a>

    <button class="menu-toggle" aria-expanded="false" aria-controls="site-nav" aria-label="Open menu">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <nav id="site-nav" class="site-nav">
        <a href="../pages/dashboard.php" class="btn-view">Dashboard</a>
        <a href="../pages/upload.php" class="btn-view">Upload</a>
        <a href="../pages/pdf_list.php" class="btn-view">PDFs</a>
        <a href="../pages/summary_list.php" class="btn-view">Summaries</a>

        <?php if ((isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) || (isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1)): ?>
            <a href="../pages/admin.php" class="btn-view">Admin</a>
        <?php endif; ?>

        <a href="../auth/logout.php" class="btn-view">
            Logout
        </a>
    </nav>
    <script src="../assets/js/main.js" defer></script>
</header>