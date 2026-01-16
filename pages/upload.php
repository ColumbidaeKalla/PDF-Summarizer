<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

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
    include "../includes/db.php";

    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM pdf_files WHERE user_id = $user_id ORDER BY uploaded_at DESC";
    $result = mysqli_query($conn, $sql);
    ?>

    <div class="page-container">
        <h2 class="page-title">Upload PDF</h2>

        <div class="upload-card">
            <div class="upload-icon">
                <i data-lucide="upload"></i>
            </div>

            <form action="../includes/upload_process.php" method="POST" enctype="multipart/form-data">
                <label class="file-label">
                    <i data-lucide="file-text"></i>
                    <span>Select a PDF file</span>
                    <input type="file" name="pdf_file" accept="application/pdf" required>
                </label>

                <button type="submit" name="upload" class="btn-primary">
                    Upload PDF
                </button>
            </form>

            <?php
            if (isset($_GET['error'])) {
                echo '<p class="error-msg">Failed to upload. Try again.</p>';
            }
            if (isset($_GET['success'])) {
                echo '<p class="success-msg">PDF uploaded successfully!</p>';
            }
            ?>

            <hr class="divider">

            <h3 class="uploaded-title">Your Uploaded PDFs</h3>

            <div class="uploaded-list">
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '
                        <div class="pdf-item-card">
                            <div class="pdf-item">
                                <i data-lucide="file"></i>
                                <span>' . htmlspecialchars($row['original_name']) . '</span>
                            </div>
                            <a href="view_pdf.php?id=' . $row['id'] . '" class="btn-view">
                                View <i data-lucide="arrow-right"></i>
                            </a>
                        </div>
                        ';
                    }
                } else {
                    echo "<p class='no-files'>No PDFs uploaded yet.</p>";
                }
                ?>
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