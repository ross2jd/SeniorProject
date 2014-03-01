<?php
// This PHP script should only run after the last step of the genomics block portal setup is completed.
// It is going to take all the information for the $_GET array and put it into a file. This file is
// going to have a unique ID that is assigned when the user first visits the website.
session_start();
include('functions.php');
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
$datapathBlocksFile = "python_scripts/datapathTemp/datapathBlocks".$fileID.".txt";

// We need to keep track of how many blocks need data files so they have unique names
$lastBlockNumber = -1;
if (file_exists($datapathBlocksFile))
{
    $blocks = make_assoc_array_from_file($fileID);
    if (isset($blocks))
    {
        $blocksToPlace = array();
        for ($i = 0; $i < count($blocks); $i++)
        {
          if (isset($blocks[$i]['dataFile']))
          {
              $file_name_exp = explode("_", $blocks[$i]['dataFile']);
              $block_num = intval(str_replace(".txt","",$file_name_exp[1]));
              if ($block_num > $lastBlockNumber)
                  $lastBlockNumber = $block_num;
          }
        }
    }
}
$lastBlockNumber += 1;
$dataFile = "datapathTemp/dataFile".$fileID."_".$lastBlockNumber."".".txt";

// Open up the file to write the block parameters too.
if (file_exists($datapathBlocksFile)) {
  $fh = fopen($datapathBlocksFile, 'a') or exit("Unable to open file! Possible permissions error");
} else {
  $fh = fopen($datapathBlocksFile, 'w') or exit("Unable to opne file! Possible permissions error");
}

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
