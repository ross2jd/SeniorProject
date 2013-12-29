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
            var blockType= document.getElementsByName("blockType")[0].value;
            var blockName = "&blockName="+document.getElementsByName("blockName")[0].value;
            
            var url = "genericResultsBlockProcessSetup.php?blockType="+blockType;
            url += blockName;
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
            width:99%;
            font-weight:bold;
            font-family:Arial,Helvetica,sans-serif;
            color:#FF0000;
            background-color:#000000;
            text-align:center;
            padding:4px;
            text-decoration:none;
            text-transform:uppercase;
            
        }
        #step1
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
        </ul>
    </div>
    <div style="width: 90%; margin: 0 auto; margin-top: 20px;">
    <table>  
    <?php
        // Here we will get the available block types for them to choose from
        
        // Create the connnection
        $con = mysqli_connect("127.0.0.1", "root", "Hockey101", "webbioblocks");
        
        // Check connection
        if (mysqli_connect_errno())
        {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        
        $result = mysqli_query($con, "SELECT * FROM generic_result_block_supported");
        if ( false===$result ) {
            printf("error: %s\n", mysqli_error($con));
        }
        
        echo("
                <tr><td>
                <label style='padding-right: 20px;'>Select the block type for the succeeding input:</label>
                </td>
                <td><select name='blockType'>
            ");
        
        while ($row = mysqli_fetch_assoc($result))
        {
            echo("<option value='".$row['block_name']."'>".$row['block_name']."</option>");
        }
        
        echo ("</select></td></tr>");
        
        // Free the result
        mysqli_free_result($result);
        
        // Close the connection
        mysqli_close($con);
        
        // Now display the form for getting the block name.
        echo("
            <tr><td>
            <label style='padding-right: 20px;'>Enter the name for input</label></td>
            <td><input type='text' name='blockName' value=''>
            </td></tr>
            ");
    ?>
    </table>
    </div>
    <table class="push_buttons_table" style="margin-top: 20px;">
        <tr>
            <td>
                <button class="push_button_left" onclick="getSelectedValues()">Create</button>
            <td>
                <button class="push_button_right" onclick="goBackToDatapath()">Cancel</button>
            </td>
        </tr>
    </table>
</body>
</div>
</html>