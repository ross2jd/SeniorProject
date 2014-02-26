<?php
    include 'functions.php';
    session_start();
    $failed =false;
    $fileID = $_COOKIE['fileID'];
    $blocks = make_assoc_array_from_file($fileID);
    $newArray = explode(",", $_GET['Intersect']);
    print_r($_GET);
    if (isset($blocks))
    {
        // We open the file so we can write our changes back to the file.
        $datapathBlocksFile = "python_scripts/datapathTemp/datapathBlocks".$fileID.".txt" or exit("Unable to open file!");
        $fh = fopen($datapathBlocksFile, 'w');
        for ($i = 0; $i < count($blocks); $i++)
        {
            if(block_has_input_properties($blocks[$i]))
            {
                // We have a block that can handle inputs.
                if(block_has_mul_inputs($blocks[$i]))
                {
                    // See how many inputs were selected for this block
                    $inputBlocks = explode(",",$_GET[get_block_name($blocks[$i])]);
                    $numInputsSelected = count($inputBlocks);
                    echo($numInputsSelected."<br />");
                    // We need to add more than one input.
                    $numInputsDefined = get_block_num_inputs($blocks[$i]);
                    echo($numInputsDefined."<br />");
                    for ($j = 1; $j <= $numInputsSelected; $j++)
                    {
                        $blocks[$i]["blockName".$j] = $inputBlocks[$j-1];
                    }
                    if ($numInputsDefined > $numInputsSelected)
                    {
                        // We have more defined than we selected so we need to remove some.
                        for ($j = $numInputsSelected+1; $j <= $numInputsDefined; $j++)
                        {
                            unset($blocks[$i]["blockName".$j]);
                        }
                    }
                    // We need to make sure that the number of inputs is up to date.
                    set_block_num_inputs($blocks[$i], $numInputsSelected);
                }
                else
                {
                    $inputBlocks = explode(",",$_GET[get_block_name($blocks[$i])]);
                    $numInputsSelected = count($inputBlocks);
                    if ($numInputsSelected > 1)
                    {
                        echo("<strong>ERROR:: To many inputs for block name ".get_block_name($blocks[$i]).
                             "</strong><br />Press back in your browser window and reconfigure your inputs");
                        $failed = true;
                    }
                    else
                    {
                        if($numInputsSelected == 0)
                        {
                            if (block_input_exist)
                                clear_block_input($blocks[$i]);
                        }
                        else
                        {
                            set_block_input($blocks[$i],$inputBlocks[0]);
                        }
                    }
                }
                print_r($blocks[$i]);
                echo("<br />");
            }
            // Use a foreach here write to the file...
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
    }
    else
    {
        echo("ERROR: Something went wrong :(");
    }
    if (!$failed)
    {
        $url = "datapath.php";
        //header( "Location: $url" );
    }
?>