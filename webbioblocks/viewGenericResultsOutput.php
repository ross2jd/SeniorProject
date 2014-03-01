<?php
include('functions.php');

echo("
     <html lang='en-US'>
        <head>
            <meta charset='utf-8'>
            <title>Web Bio Blocks</title>
            <link rel='icon' type='image/png' href='Images/webbioblocks_title.png'>
            <link rel='stylesheet' type='text/css' href='main.css'>
        </head>
        <div class='page_header'>
            <div class='page_header_img_right' height='100%'>
                <img src='Images\University_of_Cincinnati_logo.png' height='100%'/>
            </div>
            <div class='page_header_img_left' height='100%'>
                <img src='Images\webbioblocks_header.png' height='100%' />
            </div>
            <h1 class='page_title'>Results</h1>
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