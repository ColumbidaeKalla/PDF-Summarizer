<?php
/* session_start(); */
include "../includes/auth_guard.php";
include "../includes/db.php";

/* Validate extracted text ID */
if (!isset($_GET['id'])) {
    header("Location: summary_list.php");
    exit();
}

$extract_id = (int) $_GET['id'];
$user_id = $_SESSION['user_id'];

/* Fetch extraction record */
$query = mysqli_query($conn, "SELECT * FROM extracted_text WHERE id=$extract_id AND user_id=$user_id LIMIT 1");

if (!$query || mysqli_num_rows($query) == 0) {
    header("Location: pdf_list.php?error=missing");
    exit();
}

$data = mysqli_fetch_assoc($query);
$file = $data['extracted_file'];

$path = "../uploads/extracted/" . $file;

if (!file_exists($path)) {
    header("Location: pdf_list.php?error=filemissing");
    exit();
}

/* Read extracted text */
$text = file_get_contents($path);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extracted Text</title>

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

        <h2 class="page-title">Extracted Text</h2>

        <div class="upload-card" style="text-align: left;">

            <p style="color: var(--text-hover); font-size: 14px; margin-bottom: 10px;">
                Extracted on: <?php echo date("F d, Y h:i A", strtotime($data['created_at'])); ?>
            </p>

            <div class="extracted-box" style="
            background: var(--secondary);
            padding: 18px;
            border-radius: 12px;
            color: var(--text);
            height: 420px;
            overflow-y: auto;
            white-space: pre-line;
            line-height: 1.6;
            font-size: 15px;
        ">
                <?php echo htmlspecialchars($text); ?>
            </div>

            <div class="extracted-actions" style="margin-top: 20px; display: flex; gap: 10px;">
                <form action="../includes/summary_process.php" method="POST">
                    <input type="hidden" name="extract_id" value="<?php echo $data['id']; ?>">
                    <button type="submit" name="generate_summary" class="btn-primary">
                        Generate Summary
                    </button>
                </form>

                <!-- Back to PDF -->
                <a href="view_pdf.php?id=<?php echo $data['pdf_id']; ?>" class="btn-view">
                    <i data-lucide="arrow-left"></i> Back to PDF
                </a>

                <!-- Download Extracted Text -->
                <a href="../uploads/extracted/<?php echo $file; ?>" download class="btn-view">
                    <i data-lucide="download"></i> Download Text
                </a>

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