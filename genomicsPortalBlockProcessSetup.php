<?php
// This PHP script should only run after the last step of the genomics block portal setup is completed.
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
    $_SESSION['fileID'] = array();
    $_SESSION['fileID'] = str_replace(".","",$_SERVER['REMOTE_ADDR']);
    $fileID = $_SESSION['fileID'];
    
}
$datapathBlocksFile = "python_scripts/datapathTemp/datapathBlocks".$fileID.".txt" or exit("Unable to open file!");
if (file_exists($datapathBlocksFile)) {
  $fh = fopen($datapathBlocksFile, 'a') or exit("Unable to open file! Possible permissions error");
} else {
  $fh = fopen($datapathBlocksFile, 'w') or exit("Unable to opne file! Possible permissions error");
}
// We need to keep track of how many blocks need data files so they have unique names
if (isset($_SESSION['lastBlockNumber']))
{
    $lastBlockNumber = $_SESSION['lastBlockNumber'];
    $_SESSION['lastBlockNumber'] += 1;
} else
{
    $_SESSION['lastBlockNumber'] = 0;
    $lastBlockNumber = $_SESSION['lastBlockNumber'];
    $_SESSION['lastBlockNumber'] += 1;
}
$dataFile = "datapathTemp/dataFile".$lastBlockNumber."".".txt";
// Write the header to the block data
$header = "block^&^Genomics".PHP_EOL;
$blockType = "blockCat^&^website".PHP_EOL;
fwrite($fh, $header);
fwrite($fh, $blockType);
fwrite($fh, "dataFile^&^".$dataFile.PHP_EOL);
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
