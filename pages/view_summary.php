<?php
/* session_start(); */
include "../includes/auth_guard.php";
include "../includes/db.php";

/* Validate summary ID */
if (!isset($_GET['id'])) {
    header("Location: summary_list.php");
    exit();
}

$summary_id = (int) $_GET['id'];
$user_id = $_SESSION['user_id'];

/* Fetch summary */
$query = mysqli_query(
    $conn,
    "SELECT s.*, p.original_name 
     FROM summaries s
     JOIN pdf_files p ON s.pdf_id = p.id
     WHERE s.id = $summary_id AND s.user_id = $user_id
     LIMIT 1"
);

if (!$query || mysqli_num_rows($query) === 0) {
    header("Location: summary_list.php?error=notfound");
    exit();
}

$summary = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Summary</title>

    <!-- Main Css -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- Outfit Font -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="has-header has-footer">

    <?php include "../includes/header.php"; ?>

    <div class="page-container">

        <h2 class="page-title">Summary</h2>

        <div class="upload-card" style="text-align:left;">

            <p style="color: var(--text-hover); font-size:14px; margin-bottom:6px;">
                PDF: <?php echo htmlspecialchars($summary['original_name']); ?>
            </p>

            <p style="color: var(--text-active); font-size:13px; margin-bottom:15px;">
                Generated on: <?php echo date("F d, Y h:i A", strtotime($summary['created_at'])); ?>
            </p>

            <div class="extracted-box" style="
            background: var(--secondary);
            padding: 18px;
            border-radius: 12px;
            color: var(--text);
            line-height: 1.7;
            font-size: 15px;
            white-space: pre-line;
        ">
                <?php echo htmlspecialchars($summary['summary_text']); ?>
            </div>

            <div style="margin-top:20px; display:flex; gap:10px; flex-wrap:wrap;">

                <!-- Back to list -->
                <a href="summary_list.php" class="btn-view">
                    <i data-lucide="arrow-left"></i> Back
                </a>

                <!-- Back to PDF -->
                <a href="view_pdf.php?id=<?php echo $summary['pdf_id']; ?>" class="btn-view">
                    <i data-lucide="file-text"></i> View PDF
                </a>

                <!-- Download summary if file exists -->
                <?php if (!empty($summary['summary_file'])): ?>
                    <a href="../uploads/summary/<?php echo $summary['summary_file']; ?>" download class="btn-view">
                        <i data-lucide="download"></i> Download
                    </a>
                <?php endif; ?>

            </div>

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