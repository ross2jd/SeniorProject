<?php
// This PHP script should only run after the last step of the intersect block setup is completed.
// It is going to take all the information for the $_GET array and put it into a file. This file is
// going to have a unique ID that is assigned when the user first visits the website.
session_start();
if (isset($_COOKIE['fileID']))
{
    // an existing fileID is present and it must be used!
    $fileID = $_COOKIE['fileID'];
} else
{
    // a session file ID does not exist, we should create one.
    // We are going to generate a unique ID using PHPs built in function
    $uuid = uniqid("", true);
    // We cant have dots in file names
    $uuid = str_replace(".","-",$uuid);
    $fileID = $uuid;
    $expire=time()+604800; // time out after a week of inactivity from user
    setcookie("fileID", $fileID, $expire);
}
$datapathBlocksFile = "python_scripts/datapathTemp/datapathBlocks".$fileID.".txt" or exit("Unable to open file!");
if (file_exists($datapathBlocksFile)) {
  $fh = fopen($datapathBlocksFile, 'a');
} else {
  $fh = fopen($datapathBlocksFile, 'w');
}

// Write the header to the block data
$header = "block^&^Intersect".PHP_EOL;
$blockType = "blockCat^&^logical".PHP_EOL;
fwrite($fh, $header);
fwrite($fh, $blockType);
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