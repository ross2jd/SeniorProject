<!DOCTYPE html>
<?php
    include 'clearSession.php';
    include 'functions.php';
    session_start();
?>
<html lang="en-US">
<head>
    <meta charset='utf-8'>
    <title>Welcome to Web Bio Blocks</title>
    <link rel='stylesheet' type='text/css' href='main.css'>
    <link rel="stylesheet" type="text/css" href="blocks.css">  
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script>
        function getConnections() {
            var numBlocks = document.getElementsByName("numBlocks")[0].value;
            var blockNames = document.getElementsByName("blockNames");
            var radioButtons = document.getElementsByClassName("blockRadio");
            
            // Init the 2 dimensional array for inputs
            var blockInputs = new Array();
            for (var i = 0; i < numBlocks; i++)
            {
                blockInputs.push(Array());
            }
            
            // Ex for 3 blocks
            // <0><1><2> row = 0
            // <3><4><5> row = 1
            // <6><7><8> row = 2
            var row = 0;
            var col = 0;
            for (var i = 0; i < radioButtons.length; i=i+1)
            {
                if (col >= numBlocks) {
                    // increment the row and reset the column (new table row)
                    row += 1;
                    col = 0;
                }
                if (col != row) {
                    // We should ignore the case if the block references itself.
                    if (radioButtons[i].checked) {
                        // We need to look which boxes are checked.
                        blockInputs[col].push(blockNames[row].value);
                    }
                }
                col += 1;
            }
            // Create a GET string using the inputs
            getString = "?numBlocks="+numBlocks;
            for (var i = 0; i < numBlocks; i+= 1)
            {
                getString += "&"+blockNames[i].value+"="+blockInputs[i].join(',');
            }
            window.location.href = "processConnections.php"+getString;
        }
        function goToDatapath()
        {
            window.location.href = "datapath.php";
        }
    </script>
    <style>
        .connectionsTable
        {
            display: table;
            border: solid;
            border-collapse: collapse;
        }
        .connectionsTableCell
        {
            border: solid;
            padding: 6px;
        }
    </style>
</head>
<div class="wrapper"> <!-- The wrapper for the page -->
<body>
    <div class='page_header'>
        <img style='float: right; margin-left: auto; margin-right: 5px;' src='Images\help_icon.png' />
        <h1 class='page_title'>Welcome to Web Bio Blocks</h1>
    </div>
    <?php
    $fileID = $_SESSION['fileID'];
    $blocks = make_assoc_array_from_file($fileID);
    $numBlocks = count($blocks);
    echo("<input type='hidden' name='numBlocks' value='".$numBlocks."'>");
    // We are going to create a table layout where we will list the block names on the left side, and
    // along the top.
    echo("<table align='center' class='connectionsTable'>");
    for($i = 0; $i <= $numBlocks; $i++) // loop for each row in the table
    {
        if ($i > 0)
        {
            echo("<input type='hidden' name='blockNames' value='".get_block_name($blocks[$i-1])."'>");
        }
        echo("<tr>");
        for ($j = 0; $j <= $numBlocks; $j++) // loop for each column in the table
        {
            if (($i == 0) && ($j == 0))
            {
                // We should output the block name
                echo("<td></td>");
            }
            elseif ($i == 0)
            {
                // We should output the block name for the top row of the table
                echo("<td class='connectionsTableCell' align='center'>".get_block_name($blocks[$j-1])."</td>");
            }
            elseif ($j == 0)
            {
                // We should output the block name for the first column of the table
                echo("<td class='connectionsTableCell' align='center'>".get_block_name($blocks[$i-1])."</td>");
            }
            else
            {
                // We should display a radio button
                echo("<td class='connectionsTableCell' align='center'><input class='blockRadio' type='checkbox' name='".get_block_name($blocks[$i-1])."'></td>");
            }
        }
        echo("</tr>");
    }
    echo("</table>");
    echo("
        <table class='push_buttons_table'>
             <tr>
                <td>
                    <input class='push_button_left' type='button' onclick='getConnections()' value='Submit' />
                </td>
                <td>
                    <input class='push_button_right' type='button' onclick='goToDatapath()' value='Cancel' />
                </td>
            </tr>
        </table>
        ");
    ?>
    <div class='page_footer'>
        <a href='https://docs.google.com/forms/d/1NVWJW4bce8S8Ky6LjhYaRh9UQk4R3sCD85qL--KY7-o/viewform' target='_blank'>
            <img style='float: right; margin-left: auto; margin-right: 5px; margin-top: 10px;' src='Images\bug.png' height='50' width='50'>
        </a>
    </div>
</body>
</div>
</html>
