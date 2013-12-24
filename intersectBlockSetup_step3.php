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
            var numInputs = document.getElementsByName("numInputs")[0].value;
            var blockType= document.getElementsByName("blockType")[0].value;
            var criteria = document.getElementsByName("criteria")[0].value;
            
            // Now get all of the block names
            var blockNames = "";
            for (var i = 1; i <= numInputs; i++)
            {
                blockNames += "&blockName"+i+"="+document.getElementsByName("blockName"+i)[0].value;
            }
            
            var url = "intersectBlockProcessSetup.php?numInputs="+numInputs+"&blockType="+blockType;
            url += "&criteria="+criteria+blockNames;
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
        // Here we will get the available criteria for them to choose from.
        
        // Create the connnection
        $con = mysqli_connect("127.0.0.1", "root", "Hockey101", "webbioblocks");
        
        // Check connection
        if (mysqli_connect_errno())
        {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        $result = mysqli_query($con, "SELECT * FROM intersect_supported_criteria WHERE block_name='".$_GET['blockType']."'");
        if ( false===$result ) {
            printf("error: %s\n", mysqli_error($con));
        }
        
        echo("
                <tr><td>
                <label style='padding-right: 20px;'>Select the criteria for which to intersect the given blocks:</label>
                </td>
                <td><select name='criteria'>
            ");
        
        $numColumns = mysqli_num_fields($result);
        $row = mysqli_fetch_assoc($result);
        $reservedColumns = 2; // We have the id and the name column that we don't want to count in.
        for ($i = 1; $i <= $numColumns-$reservedColumns; $i++)
        {
            echo("
                    <option value='".$row['supported_'.$i]."'>".ucfirst($row['supported_'.$i])."</option>   
                ");
        }
        echo ("</select></td></tr>");
        
        // Free the result
        mysqli_free_result($result);
        
        // Close the connection
        mysqli_close($con);
        
        // We need to store the passed in values to be used in the processing stage so just put them
        // in hidden input tags.
        $numInputs = $_GET['numInputs'];
        echo ("<input type='hidden' name='numInputs' value='".$numInputs."')");
        foreach ($_GET as $key=>$val)
        {
            echo("<input type='hidden' name='".$key."' value ='".$val."'></input>");
        }
        
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