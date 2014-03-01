<!DOCTYPE html>

<html lang="en-US">
<head>
    <meta charset="utf-8">
    <title>Web Bio Blocks</title>
    <link rel='icon' type='image/png' href='Images/webbioblocks_title.png'>
    <link rel="stylesheet" type="text/css" href="main.css">
    <script>
        function goBackToDatapath()
        {
            window.location.href = "datapath.php";
        }
        function goBackToStep1()
        {
            window.location.href = "genomicsPortalBlockSetup_step1.php";
        }
        function getRadioIndex(radio_group) {
            for (var i = 0; i < radio_group.length; i++) {
                var button = radio_group[i];
                if (button.checked) {
                    return i;
                }
            }
            return -1;
        }
        function getSelectedValue()
        {
            var radio_index = getRadioIndex(document.getElementsByName("radio_button"));
            if (radio_index < 0) {
                // The user did not select a dataset!
                alert("Please select a data set to continue")
                return;
            }
            var analysis_index = getRadioIndex(document.getElementsByName("analysis_radio"));
            var dataset_tags = document.getElementsByName("data_set");
            var database_tags = document.getElementsByName("db");
            var analysis_tags = document.getElementsByName("analysis_radio");
            var url = "genomicsPortalBlockSetup_step3.php?data_set="+dataset_tags[radio_index].value+"&db="+database_tags[radio_index].value;
            url += "&analysis="+analysis_tags[analysis_index].value;
            window.location.href = url;
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
        #step2
        {
            border: thick solid #00EE00;
        }
        
        .datasets
        {
            height: 100%;
            width: 90%;
            margin: 0 auto;
            margin-top: 20px;
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
        
        .analysisOptions
        {
            height: 100%;
            width: 90%;
            margin: 0 auto;
            margin-top: 20px;
        }
        
        .analysisOptionsTable
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
        <div class='page_header_img_right' height="100%">
            <img src='Images\University_of_Cincinnati_logo.png' height="100%"/>
        </div>
        <div class='page_header_img_left' height="100%">
            <img src='Images\webbioblocks_header.png' height="100%" />
        </div>
        <h1 class="page_title">Genomics Portal Block Setup</h1>
        <ul>
            <li class="navBar" id="step1">Step 1</li>
            <li class="navBar" id="step2">Step 2</li>
            <li class="navBar" id="step3">Step 3</li>        
        </ul>
    </div>
    <!-- Here we are going to display the available data sets to analyze -->
    <div class="datasets">
<?php
    // Lets the dataset that we queried
    // XXX: This will need to change when we implement the search box!
    $datasetQueried = $_GET['dataset_name'];
    // We need to find an unused fileID.
    if ($handle = opendir('./python_scripts/genomicPortalTemp/')) {
        $inUseNumbers = array();
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                $tempExplode = explode(".", $file);
                $tempExplode1 = explode("test", $tempExplode[0]);
                $number = $tempExplode1[1];
                array_push($inUseNumbers, $number);
            }
        }
        closedir($handle);
    }
    // Now that we have a list of in use numbers lets start at zero and traverse till we find an opening
    $fileID = 0;
    foreach ($inUseNumbers as $number) {
        if ($number != $fileID)
            break;
        else
            $fileID += 1;
    }
    
    // Calling the python script to get the html table for the requested dataset
    $command = "python /var/www/webbioblocks_test/python_scripts/getDatasetResults.py ";
    $command .= " '".$datasetQueried."' ".$fileID." 2>&1";
    $pid = popen($command, "r");
    $failedToExecute = false;
    $msg = NULL;
    while( !feof( $pid ) )
    {
        // XXX: We need to implement some error catching mechanisms based on the script. fread returns all the prints
        // in the file.
        $msg = fread($pid, 256);
        flush();
        ob_flush();
        usleep(100000);
        if ($msg != NULL)
        {
            $failedToExecute = true;
            break;
        }
    }
    pclose($pid);
    
    if ($failedToExecute)
    {
        // XXX: Create a function for this!
        if (strcmp($msg,"Error:1") != 0)
        {
            echo "<p>No public datasets found!</p>";
        }
        else
        {
            echo "<p>An error occured!".$msg."</p>";
        }
    }
    else{
        // The python script has completed executing here. We should now read the file in.
        $fh = fopen("python_scripts/genomicPortalTemp/test".$fileID.".html", "r") or exit("Unable to open file!");
        while(!feof($fh))
        {
            echo fgets($fh);
        }
        fclose($fh);
        
        // We have completed reading and writing to the file. Lets remove the file to free up space on the server.
        unlink(dirname(__FILE__) . "/python_scripts/genomicPortalTemp/test".$fileID.".html") or exit("Unable to delete file!");
    }
?>
    <!-- Now we need to display the options for what type of analysis they would like to conduct -->
    <hr />
    <div class="analysisOptions">
    <table class="analysisOptionsTable">
        <tr>
            <td><input type="radio" name="analysis_radio" value="query_data" disabled=true></td>
            <td>Query Data - Not supported</td>
        </tr>
        <tr>
            <td><input type="radio" name="analysis_radio" value="diff_expression" checked=true></td>
            <td>Differential Expression</td>
        </tr>
        <tr>
            <td><input type="radio" name="analysis_radio" value="cluster_analysis" disabled=true></td>
            <td>Cluster Analysis - Not supported</td>
        </tr>
    </table>
    </div>
    <table class="push_buttons_table" style="margin-top: 20px;">
        <tr>
            <td>
                <button class="push_button_left" onclick="getSelectedValue()">Next</button>
            <td>
                <button class="push_button_right" onclick="goBackToDatapath()">Cancel</button>
                <button class="push_button_right" onclick="goBackToStep1()">Previous</button>
            </td>
        </tr>
    </table>
    </div>
    <div class='page_footer'>
        <a href='https://docs.google.com/forms/d/1NVWJW4bce8S8Ky6LjhYaRh9UQk4R3sCD85qL--KY7-o/viewform' target='_blank'>
            <img style='float: right; margin-left: auto; margin-right: 5px; margin-top: 10px;' src='Images\bug.png' height='50' width='50'>
        </a>
    </div>
</body>
</div>
</html>
