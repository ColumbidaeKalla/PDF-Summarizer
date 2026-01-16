<?php
session_start();
include "db.php";

/* Only POST allowed */
if (!isset($_POST['generate_summary'])) {
    header("Location: ../pages/summary_list.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$extract_id = (int) $_POST['extract_id'];

/* Fetch extracted text record */
$q = mysqli_query($conn, "SELECT * FROM extracted_text WHERE id=$extract_id AND user_id=$user_id LIMIT 1");

if (!$q || mysqli_num_rows($q) == 0) {
    header("Location: ../pages/summary_list.php?error=nofile");
    exit();
}

$data = mysqli_fetch_assoc($q);
$filename = $data['extracted_file'];
$pdf_id = $data['pdf_id'];

$path = "../uploads/extracted/" . $filename;

/* Read extracted text */
$text = file_get_contents($path);

/*-----------------------------------*\
RULE-BASED SUMMARY SYSTEM
\*-----------------------------------*/

/* 1. Normalize */
$cleanText = preg_replace('/\s+/', ' ', trim($text));
$cleanText = str_replace(["\n", "\r"], " ", $cleanText);

/* 2. Split into sentences */
$sentences = preg_split('/(?<=[.!?])\s+/', $cleanText);

/* 3. Scoring rules */
/* Configuration: increase number of sentences and allow longer sentences */
$SUMMARY_SENTENCES = 8; // increase this to include more content in the summary
$MIN_WORDS = 8;
$MAX_WORDS = 80; // allow longer sentences to be considered

$keywords = ['important', 'objective', 'goal', 'result', 'summary', 'conclusion', 'purpose', 'benefit', 'problem', 'method', 'approach'];
$sentenceScores = [];

foreach ($sentences as $idx => $sentence) {
    $score = 0;

    /* rule 1: sentence length (not too short, not too long) */
    $wordCount = str_word_count($sentence);
    if ($wordCount >= $MIN_WORDS && $wordCount <= $MAX_WORDS) {
        $score += 1;
    }

    /* rule 2: count keyword matches */
    foreach ($keywords as $word) {
        if (stripos($sentence, $word) !== false) {
            $score += 2;
        }
    }

    /* rule 3: sentences at beginning or end (usually more meaningful) */
    if ($idx === 0 || $idx === count($sentences) - 1) {
        $score += 1;
    }

    $sentenceScores[] = ['sentence' => $sentence, 'score' => $score, 'index' => $idx];
}

/* Sort by score (highest first), tie-breaker by original position */
usort($sentenceScores, function ($a, $b) {
    if ($b['score'] === $a['score']) return $a['index'] - $b['index'];
    return $b['score'] - $a['score'];
});

/* Take top N sentences (configurable) */
$topSentences = array_slice($sentenceScores, 0, $SUMMARY_SENTENCES);

/* Preserve original order when building the summary */
usort($topSentences, function ($a, $b) {
    return $a['index'] - $b['index'];
});

/* Build summary text */
$summary = "";
foreach ($topSentences as $s) {
    $summary .= $s['sentence'] . " ";
}
$summary = trim($summary);

/* If summary is empty (rare case) */
if (empty($summary)) {
    $summary = "Summary could not be generated. The extracted text may be too short or unreadable.";
}

/*-----------------------------------*\
Save summary
\*-----------------------------------*/

$summaryFile = time() . "_" . rand(1000, 9999) . ".txt";
file_put_contents("../uploads/summary/" . $summaryFile, $summary);

mysqli_query(
    $conn,
    "INSERT INTO summaries (user_id, pdf_id, extracted_id, summary_text, summary_file)
     VALUES ($user_id, $pdf_id, $extract_id, '" . mysqli_real_escape_string($conn, $summary) . "', '$summaryFile')"
);

header("Location: ../pages/summary_list.php?generated=1");
exit();
