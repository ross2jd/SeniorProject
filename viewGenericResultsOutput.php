<?php
include('functions.php');

echo("
     <html lang="en-US">
        <head>
            <meta charset='utf-8'>
            <title>Results</title>
            <link rel='stylesheet' type='text/css' href='main.css'>
        </head>
        <div class='page_header'>
            <img style='float: right; margin-left: auto; margin-right: 5px;' src='Images\help_icon.png' />
            <h1 class='page_title'>Welcome to Web Bio Blocks</h1>
        </div>
     ");

$resultsFile = $_GET['file'];
$htmlOutput = format_text_results_as_html($resultsFile);

echo($htmlOutput);

echo("
    <div class='page_footer'>
        <a href='https://docs.google.com/forms/d/1NVWJW4bce8S8Ky6LjhYaRh9UQk4R3sCD85qL--KY7-o/viewform' target='_blank'>
            <img style='float: right; margin-left: auto; margin-right: 5px; margin-top: 10px;' src='Images\bug.png' height='50' width='50'>
        </a>
    </div>
    </html>
    ");