<!DOCTYPE html>

<html lang="en-US">
<head>
    <meta charset="utf-8">
    <title>Web Bio Blocks</title>
    <link rel="stylesheet" type="text/css" href="main.css">
    <script>
        function goBackToDatapath()
        {
            window.location.href = "datapath.php";
        }
    </script>
    <style>
        ul
        {
        list-style-type:none;
        margin:0;
        padding:0;
        overflow:hidden;
        }
        li
        {
        float:left;
        }
        .navBar
        {
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
        #step1
        {
            border: thick solid #00EE00;
        }
        
        .datasets
        {
            height: 700px;
            width: 75%;
            margin: 0 auto;
            margin-top: 20px;
            border: thin solid #000000;
        }
        
        .datasetsTable
        {
            height: 100%;
            width: 100%;
            display: table;
        }
        
        .datasetsTable td
        {
            border: thin solid #000000;
        }
    </style>
</head>
<div class="wrapper"> <!-- The wrapper for the page -->
<body>
    <div class="page_header">
        <img style="float: right; margin-left: auto; margin-right: 5px;" src="Images\help_icon.png" />
        <h1 class="page_title">Genomics Portal Block Setup</h1>
        <ul>
            <li class="navBar" id="step1">Step 1</li>
            <li class="navBar" id="step2">Step 2</li>
            <li class="navBar" id="step3">Step 3</li>        
        </ul>
    </div>
    
    <!-- Here we should display the search box -->
    <div class="searchBox">
        
    </div>
    
    <!-- Here we are going to display the available data sets to query -->
    <div class="datasets">
        <table class="datasetsTable">
        <form name="input" action="genomicsPortalBlockSetup_step2.php" method="get"> <!-- Start the form for the dataset quey -->
        <?php
        // Here we will dynamically populate the available blocks.
        
        // Create the connnection
        $con = mysqli_connect("127.0.0.1", "root", "Hockey101", "webbioblocks");
        
        // Check connection
        if (mysqli_connect_errno())
        {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        
        $result = mysqli_query($con, 'SELECT * FROM genomics_portal_block_datasets');
        if ( false===$result ) {
            printf("error: %s\n", mysqli_error($con));
        }
        
        while ($row = mysqli_fetch_assoc($result))
        {
            //echo "<tr><td><input type='radio' name='block_name' class='radio ui-widget-content ui-corner-all' value='".$row['name']."'></td>";
            echo "<tr><td><input type='radio' name='dataset_name' value='".$row['portal_name']."'></td>";
            echo "<td style='padding: 5px'>".$row['portal_name']."</td><td style='word-wrap: break-word; padding: 5px'>".$row['description']."</td></tr>";
        }
        
        // Free the result
        mysqli_free_result($result);
        
        // Close the connection
        mysqli_close($con);
        ?>
    </table>
    <table class="push_buttons_table" style="margin-top: 20px;">
        <tr>
            <td>
                <input class="push_button_left" type="submit" value="Next">
        </form>
                <button class="push_button_right" onclick="goBackToDatapath()">Cancel</button>
            </td>
        </tr>
    </table>
    </div>
<?php

?>
</body>
</div>
</html>