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
        function getSelectedValues()
        {
            var listTypeTag = document.getElementsByName("list_type_radio");
            var listType = listTypeTag[0].value;
            var blockInputTag = document.getElementsByName("blockInput");
            var blockInput = blockInputTag[0].value;
            var blockNameTag = document.getElementsByName("BlockName");
            var blockName = blockNameTag[0].value;
            var identifierTag = document.getElementsByName("identifier");
            var identifier = identifierTag[0].value;
            
            var url = "davidBlockProcessSetup.php?listType="+listType+"&BlockName="+blockName+"&identifier="+identifier+"&blockInput="+blockInput;
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
        #step3
        {
            border: thick solid #00EE00;
        }
        .listOptions
        {
            height: 100%;
            width: 90%;
            margin: 0 auto;
            margin-top: 20px;
        }
        
        .listOptionsTable
        {
            height: 100%;
            width: 100%;
            display: table;
        }
    </style>
</head>
<div class="wrapper"> <!-- The wrapper for the page -->
<body>
    <div class="page_header">
        <img style="float: right; margin-left: auto; margin-right: 5px;" src="Images\help_icon.png" />
        <h1 class="page_title">DAVID Block Setup</h1>
        <ul class="navBarList">
            <li class="navBar" id="step1">Step 1</li>
            <li class="navBar" id="step2">Step 2</li>
            <li class="navBar" id="step3">Step 3</li>        
        </ul>
    </div>
    <div style="width: 90%; margin: 0 auto; margin-top: 20px;">
    <label style="padding-right: 20px;">Select the list type</label>
    <div class="listOptions">
        <table class="listOptionsTable">
        <tr>
            <td><input type="radio" name="list_type_radio" value="gene_list" checked=true></td>
            <td>Gene List</td>
        </tr>
        <tr>
            <td><input type="radio" name="list_type_radio" value="background"></td>
            <td>Background</td>
        </tr>
        </table>
        <?php
        foreach ($_GET as $key=>$val)
        {
            echo("<input type='hidden' name='".$key."' value ='".$val."'></input>");
        }
        ?>
    </div>
    <label style="padding-right: 20px;">Enter the name you want to give this block</label>
    <input type="text" name="BlockName" value="">
    </div>
    <table class="push_buttons_table" style="margin-top: 20px;">
        <tr>
            <td>
                <button class="push_button_left" onclick="getSelectedValues()">Next</button>
            <td>
                <button class="push_button_right" onclick="goBackToDatapath()">Cancel</button>
            </td>
        </tr>
    </table>
    <div class='page_footer'>
        <a href='https://docs.google.com/forms/d/1NVWJW4bce8S8Ky6LjhYaRh9UQk4R3sCD85qL--KY7-o/viewform' target='_blank'>
            <img style='float: right; margin-left: auto; margin-right: 5px; margin-top: 10px;' src='Images\bug.png' height='50' width='50'>
        </a>
    </div>
</body>
</div>
</html>