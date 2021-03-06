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
            if (isset($components[1]))
	        $block[$components[0]] = $components[1];
	    else
                $block[$components[0]] = NULL;
        }
    }
    fclose($file);
    return $blocks;
}

function get_block_name($block)
{
    if ($block['block'] == 'Genomics' || $block['block'] == 'Generic Result' || $block['block'] == 'DAVID')
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

function format_text_results_as_html($resultsFile)
{
    $filePath = 'python_scripts/'.$resultsFile;
    if (file_exists($filePath)) {
        $file = fopen($filePath, 'r');
    } else {
        $html = "You must click run before you see data!";
        return $html;
    }
    $html = "<table>";
    while (! feof($file))
    {
        $line = fgets($file);
        $html .= "<tr><td>".str_replace("\n","",$line)."</td></tr>";
    }
    $html .= "</table><br /><a href='".$filePath."'>View Raw Data File</a>";
    return $html;
}

function get_url_from_file($dataFile)
{
    $filePath = 'python_scripts/'.$dataFile;
    if (file_exists($filePath)) {
        $file = fopen($filePath, 'r');
    } else {
        $url = "";
        return $url;
    }
    $url = fgets($file);
    return $url;
}

function block_has_input_properties($block)
{
    if ($block['block'] == 'Generic Result' || $block['block'] == 'Intersect' || $block['block'] == 'DAVID')
    {
        return true;
    }
    return false;
}

function block_has_mul_inputs($block)
{
    if ($block['block'] == 'Intersect')
    {
        return true;
    }
    return false;
}

function get_block_num_inputs($block)
{
    if ($block['block'] == 'Intersect')
    {
        return $block['numInputs'];
    }
    return 1;
}

function set_block_num_inputs(&$block, $numInputs)
{
    if ($block['block'] == 'Intersect')
    {
        $block['numInputs'] = $numInputs;
    }
}

function set_block_input(&$block, $inputBlockName)
{
    if ($block['block'] == 'Generic Result')
    {
        $block['blockName'] = $inputBlockName;
    }
    elseif($block['block'] == 'DAVID')
    {
        $block['blockInput'] = $inputBlockName;
    }
}

function block_input_exist($block)
{
    if ($block['block'] == 'Generic Result')
    {
        if (isset($block['blockName']))
            return true;
    }
    elseif($block['block'] == 'DAVID')
    {
        if (isset($block['blockInput']))
            return true;
    }
    else
    {
        return false;
    }
}

function clear_block_input(&$block)
{
    if ($block['block'] == 'Generic Result')
    {
        unset($block['blockName']);
    }
    elseif($block['block'] == 'DAVID')
    {
        unset($block['blockInput']);
    }
}

function get_block_input_names($block)
{
    $blockNames = array();
    if ($block['block'] == 'Intersect')
    {
        for ($i = 1; $i <= $block['numInputs']; $i++)
        {
            array_push($blockNames, $block['blockName'.$i]);
        }
    }
    elseif ($block['block'] == 'Generic Result')
    {
        array_push($blockNames, $block['blockName']);
    }
    elseif ($block['block'] == 'DAVID')
    {
        array_push($blockNames, $block['blockInput']);
    }
    return $blockNames;
}

function draw_line($x1, $x2, $y1, $y2)
{
    if ($y1 > $y2)
    {
        $heightDiv = $y1-$y2;
        $widthDiv = abs($x1-$x2);
        $line = "<div style='left: ".$x2."px; top: ".$y2."px; width: ".$widthDiv."px; height: ".$heightDiv."px; position: absolute;'>";
        $line .= "<svg width='100%' height='100%'>";
        $line .= "<line x1='100%' y1='100%' x2='0' y2='0' stroke-width='2' stroke='black'/>";
    }
    else
    {
        $heightDiv = $y2-$y1;
        if ($heightDiv == 0)
            $heightDiv = 2;
        $widthDiv = abs($x1-$x2);
        $adjustedY2 = $y2-$heightDiv;
        $line = "<div style='left: ".$x2."px; top: ".$adjustedY2."px; width: ".$widthDiv."px; height: ".$heightDiv."px; position: absolute;'>";
        $line .= "<svg width='100%' height='100%'>";
        $line .= "<line x1='100%' y1='0' x2='0' y2='100%' stroke-width='2' stroke='black'/>";
    }
    $line .=  "Sorry, your browser does not support inline SVG.";
    $line .= "</svg>";
    $line .= "</div>";
    echo($line);
}

function draw_connector_lines_for_blocks($blocks)
{
    $blockHeight = 125;
    $blockWidth = 125;
    for ($i = 0; $i < count($blocks); $i++)
    {
        $curBlock = $blocks[$i];
        // Loop through the array of blocks that we have so we can draw lines
        if (block_has_input_properties($curBlock))
        {
            // This block takes an input so we are going to draw a line between this block
            // and the inputted block.
            $top = intval(str_replace("px", "", get_block_y_position($curBlock)));
            $left = intval(str_replace("px", "", get_block_x_position($curBlock)));
            $circleL_Y = $top + $blockHeight/2;
            $circleL_X = $left;
            $blockNames = get_block_input_names($curBlock);
            if (!$blockNames || ($top == 0 && $left == 0))
            {
                // There are no inputs or the block was just placed!
                return;
            }
            for ($j = 0; $j < count($blockNames); $j++)
            {
                $index = find_block_index_by_name($blockNames[$j],$blocks);
                if ($index == -1)
                    return;
                $input_top = intval(str_replace("px", "", get_block_y_position($blocks[$index])));
                $input_left = intval(str_replace("px", "", get_block_x_position($blocks[$index])));
                $circleR_X = $input_left + $blockWidth;
                $circleR_Y = $input_top + $blockHeight/2;
                draw_line($circleL_X, $circleR_X+2, $circleL_Y, $circleR_Y);
            }
        }
    }
}
?>
