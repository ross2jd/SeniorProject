 <?php
    header('Content-Type: text/html; charset=utf-8');
    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
    echo "<style type='text/css'>
     body{
     background:#000;
     color: #7FFF00;
     font-family:'Lucida Console',sans-serif !important;
     font-size: 12px;
     }
     a:visited {color:#7FFF00;}  /* visited link */
     </style>";
    /// This php script will handle calling the python script that will compile and execute our blocks
    /// Calling the python script to get the html table for the requested dataset
    $file = "datapathTemp/datapathBlocks".$_COOKIE['fileID'].".txt";
    $command = "python /var/www/webbioblocks_test/python_scripts/processDatapathFile.py ";
    $command .= " $file 2>&1";
    $pid = popen($command, "r");
    $failedToExecute = false;
    $msg = NULL;
    echo "<body><pre>";
    while( !feof( $pid ) )
    {
        // XXX: We need to implement some error catching mechanisms based on the script. fread returns all the prints
        // in the file.
        $msg = fread($pid, 256);
        echo $msg;
        flush();
        ob_flush();
        echo "<script>window.scrollTo(0,99999);</script>";
        usleep(100000);
    }
    pclose($pid);
    echo "</pre><script>window.scrollTo(0,99999);</script>";
    echo "<br /><br /><a href='datapath.php'>Return To Datapath</a><br /><br />";
?>
    