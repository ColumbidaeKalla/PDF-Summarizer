<?php
include "../includes/auth_guard.php";
include "../includes/header.php";
include "../includes/db.php";

$user_id = $_SESSION['user_id'];

/* Get all PDFs uploaded by this user */
$sql = "SELECT * FROM pdf_files WHERE user_id = $user_id ORDER BY uploaded_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your PDFs</title>

    <!-- Main Css -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- Outfit Font -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="has-header has-footer">
    <div class="page-container">
        <h2 class="page-title">Your Uploaded PDFs</h2>


        <div class="pdf-list-container">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '
                    <div class="pdf-item-card">
                        <div class="pdf-left">
                            <i data-lucide="file-text"></i>
                            <div class = "pdf-info">
                                <p class="pdf-name">' . htmlspecialchars($row['original_name']) . '</p>
                                <p class="pdf-date">Uploaded: ' . $row['uploaded_at'] . '</p>
                            </div>
                        </div>
            
                        <a href="view_pdf.php?id=' . $row['id'] . '" class="btn-view">
                            View <i data-lucide="arrow-right"></i>
                        </a>
                    </div>
                    ';
                }
            } else {
                echo "<p class = 'no-files'>You haven't uploaded any PDFs yet.</p>";
            }
            ?>
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