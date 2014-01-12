<?php
include('functions.php');

$resultsFile = $_GET['file'];
$htmlOutput = format_text_results_as_html($resultsFile);

echo($htmlOutput);