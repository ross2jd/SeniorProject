<!DOCTYPE html>

<html lang="en-US">
<head>
    <meta charset="utf-8">
    <title>Web Bio Blocks</title>
    <link rel="stylesheet" type="text/css" href="main.css"></link>
    <link rel="stylesheet" type="text/css" href="css/mktree.css"></link>
    <script>
        function goBackToDatapath()
        {
            window.location.href = "datapath.php";
        }
        function goBackToStep2()
        {
            window.history.back();
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
        function getCheckedBoxes(checkBoxGroup) {
            checkedBoxes = "";
            for (var i = 0; i < checkBoxGroup.length; i++) {
                var button = checkBoxGroup[i];
                if (button.checked) {
                    checkedBoxes += button.value+",";
                }
            }
            return checkedBoxes;
        }
        function getSelectedValues()
        {
            var incOrEx_index = getRadioIndex(document.getElementsByName("includeORexclude"));
            if (incOrEx_index < 0) {
                // The user did not select if to include or exclude!
                alert("Please whether to include or to exclude the data")
                return;
            }
            var checkedBoxes = getCheckedBoxes(document.getElementsByName("filterchk"));
            var prop_index = getRadioIndex(document.getElementsByName("prop"));
            if (incOrEx_index < 0) {
                // The user did not select a sample grouping
                alert("Please select a sample grouping")
                return;
            }
            var dataset_tag = document.getElementsByName("data_set");
            var database_tag = document.getElementsByName("db");
            var incORex_tags = document.getElementsByName("includeORexclude");
            var prop_tags = document.getElementsByName("prop");
            var blockName_tag = document.getElementsByName("BlockName");
            var p_or_q_value_tag = document.getElementsByName("p_or_q_value");
            var sigcutoff_tag = document.getElementsByName("sigcutoff");
            var foldchange_tag = document.getElementsByName("foldchange");
            var ifCluster_tag = document.getElementsByName("ifCluster");
            var output_tag = document.getElementsByName("output");
            var output_index = getRadioIndex(output_tag);
            // XXX: Add in validation for any missing entries!
            
            // Now we create the URL to pass to the PHP script.
            var url = "genomicsPortalBlockProcessSetup.php?data_set="+dataset_tag[0].value+"&db="+database_tag[0].value;
            url += "&includeORexclude="+incORex_tags[incOrEx_index].value+"&prop="+prop_tags[prop_index].value;
            url += "&filterchk="+checkedBoxes+"&BlockName="+blockName_tag[0].value+"&p_or_q_value="+p_or_q_value_tag[0].value;
            url += "&sigcutoff="+sigcutoff_tag[0].value+"&foldchange="+foldchange_tag[0].value+"&ifCluster="+ifCluster_tag[0].value;
            url += "&output="+output_tag[output_index].value;
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
        th
        {
            text-align: left;
        }
    </style>
</head>
<div class="wrapper"> <!-- The wrapper for the page -->
<body>
    <div class="page_header">
        <img style="float: right; margin-left: auto; margin-right: 5px;" src="Images\help_icon.png" />
        <h1 class="page_title">Genomics Portal Block Setup</h1>
        <ul class="navBarList">
            <li class="navBar" id="step1">Step 1</li>
            <li class="navBar" id="step2">Step 2</li>
            <li class="navBar" id="step3">Step 3</li>        
        </ul>
    </div>
    
<?php
    // Lets the dataset that we queried
    $datasetQueried = $_GET['data_set'];
    // Get the database that was queried also
    $dbQueried = $_GET['db'];
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
    $command = "python /var/www/webbioblocks_test/python_scripts/getDatasetFormOptions.py ";
    $command .= " '".$datasetQueried."' '".$dbQueried."' ".$fileID." 2>&1";
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
        echo "<p>An error occured!".$msg."</p>";
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
    <!-- Now we want to add a step for the user to name the block -->
    <table>
        <thead>
            <tr>
                <th colspan=2>Step 5: Specify the name of your block (for your use)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Name for block</td>
                <td><input type="text" name="BlockName" value=""></td>
            </tr>
        </tbody>
    </table>
    <!-- Last thing we want to do is aks for what type of output they want -->
    <hr />
    <table>
        <thead>
            <tr>
                <th colspan=2>Step 6: Specify what type of output you would like</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="radio" name="output" value="inter_tree_orig"></td>
                <td>Interactive Tree Browsing - Originial Data (No succeeding blocks allowed)</td>
            </tr>
            <tr>
                <td><input type="radio" name="output" value="inter_tree_center"></td>
                <td>Interactive Tree Browsing - Centered Data (No succeeding blocks allowed)</td>
            </tr>
            <tr>
                <td><input type="radio" name="output" value="raw_data"></td>
                <td>Tabular format - Raw data (Succeeding blocks allowed)</td>
            </tr>
        </tbody>
    </table>
    <table class="push_buttons_table" style="margin-top: 20px;">
        <tr>
            <td>
                <button class="push_button_left" onclick="getSelectedValues()">Create</button>
            <td>
                <button class="push_button_right" onclick="goBackToDatapath()">Cancel</button>
                <button class="push_button_right" onclick="goBackToStep2()">Previous</button>
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
