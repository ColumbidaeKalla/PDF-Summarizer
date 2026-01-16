<?php
session_start();

require_once "../vendor/autoload.php";
include "db.php";

use Smalot\PdfParser\Parser;

if (!isset($_POST['extract'])) {
    header("Location: ../pages/pdf_list.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$pdf_id  = (int) $_POST['pdf_id'];

/* Get PDF info */
$query = mysqli_query(
    $conn,
    "SELECT filename FROM pdf_files 
     WHERE id = $pdf_id AND user_id = $user_id 
     LIMIT 1"
);

if (!$query || mysqli_num_rows($query) === 0) {
    header("Location: ../pages/pdf_list.php?error=nofile");
    exit();
}

$row = mysqli_fetch_assoc($query);
$filename = $row['filename'];

$pdfPath = "../uploads/pdf/" . $filename;

if (!file_exists($pdfPath)) {
    header("Location: ../pages/pdf_list.php?error=filemissing");
    exit();
}

try {
    /* ✅ PDFParser extraction */
    $parser = new Parser();
    $pdf = $parser->parseFile($pdfPath);
    $text = trim($pdf->getText());

    if (empty($text)) {
        throw new Exception("Empty extracted text");
    }

    /* Save extracted text */
    $newName = time() . "_" . rand(1000, 9999) . ".txt";
    file_put_contents("../uploads/extracted/" . $newName, $text);

    /* Save to database */
    mysqli_query(
        $conn,
        "INSERT INTO extracted_text (user_id, pdf_id, extracted_file)
         VALUES ($user_id, $pdf_id, '$newName')"
    );

    header("Location: ../pages/view_pdf.php?id=$pdf_id&extracted=1");
    exit();
} catch (Exception $e) {
    header("Location: ../pages/view_pdf.php?id=$pdf_id&error=extractfail");
    exit();
}
