<?php
include 'functions.php';
session_start();

$blocks = $_SESSION['placedBlocks'];

foreach($_GET as $name=>$value)
{
    if (strcmp($name,"nextPage") == 0)
    {
        $url = $value;
    }
    else
    {
        $blockName = str_replace("_", " ", $name);
        $pos = explode(",", $value);
        $y = $pos[0];
        $x = $pos[1];
        $index = find_block_index_by_name($blockName, $blocks);
        if ($index != -1)
        {
            $blocks[$index]['xPos'] = $x;
            $blocks[$index]['yPos'] = $y;
        }
        else
        {
            // ignore the error for now
            //exit("Error: No block was found!");
        }
    }
}

$fileID = $_SESSION['fileID'];
$datapathBlocksFile = "python_scripts/datapathTemp/datapathBlocks".$fileID.".txt" or exit("Unable to open file!");
$fh = fopen($datapathBlocksFile, 'w');
// Now just write the associative array to the file
for ($i = 0; $i < count($blocks); $i++)
{
    foreach($blocks[$i] as $name=>$value)
    {
        $line = $name."^&^".$value.PHP_EOL;
        fwrite($fh, $line);
    }
    // Write the footer to the block data
    $footer = "****".PHP_EOL;
    fwrite($fh, $footer);
}
fclose($fh);
header( "Location: $url" );
?>