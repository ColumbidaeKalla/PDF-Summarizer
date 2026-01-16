<?php
// session_start();
include "../includes/auth_guard.php";
include "../includes/db.php";

// Get PDF ID
if (!isset($_GET['id'])) {
    header("Location: pdf_list.php");
    exit();
}

$pdf_id = (int) $_GET['id'];
$user_id = $_SESSION['user_id'];

// Fetch PDF info
$query = mysqli_query($conn, "SELECT * FROM pdf_files WHERE id=$pdf_id AND user_id=$user_id LIMIT 1");

if (!$query || mysqli_num_rows($query) == 0) {
    header("Location: pdf_list.php?error=nofile");
    exit();
}

$pdf = mysqli_fetch_assoc($query);

// Check if extracted file exists
$extractCheck = mysqli_query($conn, "SELECT * FROM extracted_text WHERE pdf_id=$pdf_id AND user_id=$user_id LIMIT 1");
$extracted = ($extractCheck && mysqli_num_rows($extractCheck) > 0) ? mysqli_fetch_assoc($extractCheck) : null;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View PDF</title>

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

        <h2 class="page-title"><?php echo $pdf['original_name']; ?></h2>

        <div class="upload-card">

            <p style="color: var(--text-hover); font-size: 15px; margin-bottom: 20px;">
                Uploaded on: <?php echo date("F d, Y h:i A", strtotime($pdf['uploaded_at'])); ?>
            </p>

            <!-- Extract Text Button -->
            <form action="../includes/extract_text.php" method="POST" style="margin-bottom: 20px;">
                <input type="hidden" name="pdf_id" value="<?php echo $pdf['id']; ?>">
                <button type="submit" name="extract" class="btn-primary">
                    Extract Text
                </button>
            </form>

            <!-- Show success message -->
            <?php if (isset($_GET['extracted'])): ?>
                <p class="success-msg">Text extracted successfully!</p>
            <?php endif; ?>

            <!-- If extracted file exists, show a link -->
            <?php if ($extracted): ?>
                <a href="view_extracted.php?id=<?php echo $extracted['id']; ?>" class="btn-view" style="margin-top: 10px;">
                    <i data-lucide="file-text"></i> View Extracted Text
                </a>
            <?php endif; ?>

            <div class="divider"></div>

            <a href="../uploads/pdf/<?php echo $pdf['filename']; ?>" target="_blank" class="btn-view" style="margin-top: 15px;">
                <i data-lucide="file"></i> Open Original PDF
            </a>

        </div>

    </div>

    <?php include "../includes/footer.php"; ?>

    <script>
        lucide.createIcons();
    </script>

</body>

</html>