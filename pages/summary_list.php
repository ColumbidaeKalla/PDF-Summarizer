<?php
/* session_start(); */
include "../includes/auth_guard.php";
include "../includes/db.php";

$user_id = $_SESSION['user_id'];

/* Fetch summaries */
$query = mysqli_query(
    $conn,
    "SELECT s.*, p.original_name 
     FROM summaries s
     JOIN pdf_files p ON s.pdf_id = p.id
     WHERE s.user_id = $user_id
     ORDER BY s.created_at DESC"
);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Summaries</title>

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

        <h2 class="page-title">Generated Summaries</h2>

        <?php if (isset($_GET['generated'])): ?>
            <p class="success-msg">Summary generated successfully.</p>
        <?php endif; ?>

        <div class="pdf-list-container">

            <?php if ($query && mysqli_num_rows($query) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($query)): ?>
                    <div class="pdf-item-card">

                        <div class="pdf-left">
                            <i data-lucide="book-open"></i>

                            <div class="pdf-info">
                                <h3 class="pdf-name">
                                    <?php echo htmlspecialchars($row['original_name']); ?>
                                </h3>
                                <p class="pdf-date">
                                    <?php echo date("F d, Y h:i A", strtotime($row['created_at'])); ?>
                                </p>
                            </div>
                        </div>

                        <a href="view_summary.php?id=<?php echo $row['id']; ?>" class="btn-view">
                            <i data-lucide="eye"></i> View Summary
                        </a>

                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-files">No summaries generated yet.</p>
            <?php endif; ?>

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