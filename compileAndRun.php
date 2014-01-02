<?php
/// This php script will handle calling the python script that will compile and execute our blocks
// Calling the python script to get the html table for the requested dataset
    session_start();
    // TODO: We have problems with sessions... we will have to hardcode for now...
    $file = "datapathTemp/datapathBlocks".$_SESSION['fileID'].".txt";
    $command = "python /var/www/webbioblocks_test/python_scripts/processDatapathFile.py ";
    $command .= " '".$file."' 2>&1";
    $pid = popen($command, "r");
    $failedToExecute = false;
    $msg = NULL;
    while( !feof( $pid ) )
    {
        // XXX: We need to implement some error catching mechanisms based on the script. fread returns all the prints
        // in the file.
        $msg = fread($pid, 256);
        echo $msg;
        flush();
        ob_flush();
        usleep(100000);
    }
    pclose($pid);
    $url = "datapath.php";
    header( "Location: $url" );
?>