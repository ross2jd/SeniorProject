<?php

function make_assoc_array_from_file($fileID)
{
    $datapathBlocksFile = "python_scripts/datapathTemp/datapathBlocks".$fileID.".txt";
    
    if (file_exists($datapathBlocksFile)) {
        $file = fopen($datapathBlocksFile, 'r');
    } else {
        return;
    }
    $footer = "****";
    $block = array();
    $blocks = array();
    
    // Now we are going to tread the file, line by line
    while (!feof($file))
    {
        $line = fgets($file);
        $line = str_replace(PHP_EOL, '', $line);
        if (strcmp($line, $footer) == 0)
        {
            array_push($blocks,$block);
            $block = array();
        }
        else
        {
            $components =  explode("^&^", $line);
            $block[$components[0]] = $components[1];
        }
    }
    fclose($file);
    return $blocks;
}

function get_block_name($block)
{
    if ($block['block'] == 'Genomics' || $block['block'] == 'Generic Result')
    {
        return $block['BlockName'];
    }
    elseif($block['block'] == 'Intersect')
    {
        return $block['blockName0'];
    }
    else
    {
        return NULL;    
    }
}

function get_block_class_name($block)
{
    $blockAttr = $block['block'];
    $blockAttr = str_replace(" ", "_", $blockAttr)."_Block";
    return $blockAttr;
}

function get_block_id_name($blockName)
{
    $blockIdName = str_replace(" ", "_", $blockName);
    return $blockIdName;
}

function get_block_x_position(&$block)
{
    if (isset($block['xPos']))
    {
        // There exisits a coordinate.
        return $block['xPos'];
    }
    else
    {
        $block['xPos'] = 0;
        return 0;
    }
}

function get_block_y_position(&$block)
{
    if (isset($block['yPos']))
    {
        // There exisits a coordinate.
        return $block['yPos'];
    }
    else
    {
        $block['yPos'] = 0;
        return 0;
    }
}

function find_block_index_by_name($name, $blocks)
{
    for ($i = 0; $i < count($blocks); $i++)
    {
        $blockName = get_block_name($blocks[$i]);
        if (strcmp($blockName, $name) == 0)
            return $i;
    }
    return -1;
}
?>