<?php
// This PHP script should only run after the last step of the intersect block setup is completed.
// It is going to take all the information for the $_GET array and put it into a file. This file is
// going to have a unique ID that is assigned when the user first visits the website.
session_start();
if (isset($_SESSION['fileID']))
{
    // an existing fileID is present and it must be used!
    $fileID = $_SESSION['fileID'];
} else
{
    // an session file ID does not exist, we should create one.
    // we are going to use the users IP address
    $_SESSION['fileID'] = str_replace(".","",$_SERVER['REMOTE_ADDR']);
    $fileID = $_SESSION['fileID'];
    
}

// We need to find an unused fileID.
if ($handle = opendir('./python_scripts/genericResultsTemp/')) {
    $inUseNumbers = array();
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            $tempExplode = explode(".", $file);
            $tempExplode1 = explode("test", $tempExplode[0]);
            $number = $tempExplode1[1];
            array_push($inUseNumbers, $number);
        }
    }
    closedir($handle);
}
// Now that we have a list of in use numbers lets start at zero and traverse till we find an opening
$results_fileID = 0;
foreach ($inUseNumbers as $number) {
    if ($number != $results_fileID)
        break;
    else
        $results_fileID += 1;
}

// Now lets create the name of the file to store the results data to.
$results_file = "genericResultsTemp/resultsData".$results_fileID.".txt";

$datapathBlocksFile = "python_scripts/datapathTemp/datapathBlocks".$fileID.".txt" or exit("Unable to open file!");
if (file_exists($datapathBlocksFile)) {
  $fh = fopen($datapathBlocksFile, 'a');
} else {
  $fh = fopen($datapathBlocksFile, 'w');
}

// Write the header to the block data
$header = "block^&^Generic Result".PHP_EOL;
$blockType = "blockCat^&^logical".PHP_EOL;
fwrite($fh, $header);
fwrite($fh, $blockType);
fwrite($fh, "resultsFile^&^".$results_file.PHP_EOL);
// Use a foreach here write to the file...
foreach($_GET as $name=>$value)
{
    $line = $name."^&^".$value.PHP_EOL;
    fwrite($fh, $line);
}
// Write the footer to the block data
$footer = "****".PHP_EOL;
fwrite($fh, $footer);
fclose($fh);
$url = "datapath.php";
header( "Location: $url" );
?>