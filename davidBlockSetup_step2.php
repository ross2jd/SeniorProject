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
            window.location.href = "davidBlockSetup_step1.php";
        }
        function getSelectedValues()
        {
            var blockNamesTag = document.getElementsByName("blockInput");
            var blockName = blockNamesTag[0].value;
            var identifierTag = document.getElementsByName("identifier");
            var identifier = identifierTag[0].value;
            
            var url = "davidBlockSetup_step3.php?blockInput="+blockName+"&identifier="+identifier;
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
        // Here we will get the available block types for them to choose from
        
        // Create the connnection
        $con = mysqli_connect("127.0.0.1", "root", "Hockey101", "webbioblocks");
        
        // Check connection
        if (mysqli_connect_errno())
        {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        
        $result = mysqli_query($con, "SELECT * FROM david_block_identifer");
        if ( false===$result ) {
            printf("error: %s\n", mysqli_error($con));
        }
        
        echo("
                <tr><td>
                <label style='padding-right: 20px;'>Select the identifier for the gene names</label>
                </td>
                <td><select name='identifier'>
            ");
        
        while ($row = mysqli_fetch_assoc($result))
        {
            echo("<option value='".$row['identifier']."'>".$row['identifier']."</option>");
        }
        
        echo ("</select></td></tr>");
        
        // Free the result
        mysqli_free_result($result);
        
        // Close the connection
        mysqli_close($con);
        
        $blockName = $_GET['blockInput'];
        echo ("<input type='hidden' name='blockInput' value='".$blockName."'>");
        
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
    <div class='page_footer'>
        <a href='https://docs.google.com/forms/d/1NVWJW4bce8S8Ky6LjhYaRh9UQk4R3sCD85qL--KY7-o/viewform' target='_blank'>
            <img style='float: right; margin-left: auto; margin-right: 5px; margin-top: 10px;' src='Images\bug.png' height='50' width='50'>
        </a>
    </div>
</body>
</div>
</html>