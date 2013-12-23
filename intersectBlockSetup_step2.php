<!DOCTYPE html>

<html lang="en-US">
<head>
    <meta charset="utf-8">
    <title>Web Bio Blocks</title>
    <link rel="stylesheet" type="text/css" href="main.css"></link>
    <script>
        function goBackToDatapath()
        {
            window.location.href = "datapath.php";
        }
        function goBackToStep1()
        {
            window.location.href = "intersectBlockSetup_step1.php";
        }
        function getSelectedValues()
        {
            var numInputsTag = document.getElementsByName("numInputs");
            var numInputs = numInputsTag[0].value;
            
            var blockNamesUrlString = "";
            for (var i = 1; i <= numInputs; i++)
            {
                blockNamesUrlString += "&blockName"+i+"="+document.getElementsByName("blockName"+i)[0].value;
            }
            var url = "intersectBlockSetup_step3.php?numInputs="+numInputs+blockNamesUrlString;
            window.location.href = url;
        }
    </script>
    <style>
        .navBarList
        {
        list-style-type:none;
        margin:0;
        padding:0;
        overflow:hidden;
        }
        .navBar
        {
            float:left;
            display:block;
            width:32%;
            font-weight:bold;
            font-family:Arial,Helvetica,sans-serif;
            color:#FF0000;
            background-color:#000000;
            text-align:center;
            padding:4px;
            text-decoration:none;
            text-transform:uppercase;
            
        }
        #step2
        {
            border: thick solid #00EE00;
        }
    </style>
</head>
<div class="wrapper"> <!-- The wrapper for the page -->
<body>
    <div class="page_header">
        <img style="float: right; margin-left: auto; margin-right: 5px;" src="Images\help_icon.png" />
        <h1 class="page_title">Intersect Block Setup</h1>
        <ul class="navBarList">
            <li class="navBar" id="step1">Step 1</li>
            <li class="navBar" id="step2">Step 2</li>
            <li class="navBar" id="step3">Step 3</li>        
        </ul>
    </div>
    <div style="width: 90%; margin: 0 auto; margin-top: 20px;">
    <table>  
    <?php
        $numInputs = $_GET['numInputs'];
        echo ("<input type='hidden' name='numInputs' value='".$numInputs."')");
        for ($i = 1; $i <= $numInputs; $i++)
        {
            echo("
                    <tr><td>
                    <label style='padding-right: 20px;'>Enter the name for input ".$i."</label>
                    <input type='text' name='blockName".$i."' value=''>
                    </tr></td>
                 ");
        }
    ?>
    </table>
    </div>
    <table class="push_buttons_table" style="margin-top: 20px;">
        <tr>
            <td>
                <button class="push_button_left" onclick="getSelectedValues()">Next</button>
            <td>
                <button class="push_button_right" onclick="goBackToDatapath()">Cancel</button>
                <button class="push_button_right" onclick="goBackToStep1()">Previous</button>
            </td>
        </tr>
    </table>
</body>
</div>
</html>