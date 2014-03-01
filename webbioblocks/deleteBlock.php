<?php
    include 'functions.php';
    session_start();
    $fileID = $_COOKIE['fileID'];
    $blocks = make_assoc_array_from_file($fileID);
    $datapathBlocksFile = "python_scripts/datapathTemp/datapathBlocks".$fileID.".txt" or exit("Unable to open file!");
    $fh = fopen($datapathBlocksFile, 'w');
    // Now just write the associative array to the file
    for ($i = 0; $i < count($blocks); $i++)
    {
        if (strcmp(get_block_name($blocks[$i]), $_GET['block_id']) != 0)
        {
            // Only write blocks that we have not selected
            foreach($blocks[$i] as $name=>$value)
            {
                $line = $name."^&^".$value.PHP_EOL;
                fwrite($fh, $line);
            }
            // Write the footer to the block data
            $footer = "****".PHP_EOL;
            fwrite($fh, $footer);
        }
    }
    fclose($fh);
    header( "Location: datapath.php" );
?>