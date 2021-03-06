<!DOCTYPE html>
<?php
    include 'clearSession.php';
    include 'functions.php';
    session_start();
?>
<html lang="en-US">
<head profile="http://www.w3.org/2005/10/profile">
    <meta charset='utf-8'>
    <title>Welcome to Web Bio Blocks</title>
    <link rel='icon' type='image/png' href='Images/webbioblocks_title.png'>
    <link rel='stylesheet' type='text/css' href='main.css'>
    <link rel="stylesheet" type="text/css" href="blocks.css">  
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <style>
        body { font-size: 62.5%; }
        fieldset { padding:0; border:0; margin-top:25px; }
        h1 { font-size: 1.2em; margin: .6em 0; }
        div#users-contain { width: 350px; margin: 20px 0; }
        div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
        div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
        .ui-dialog .ui-state-error { padding: .3em; }
        .validateTips { border: 1px solid transparent; padding: 0.3em; }
    </style>
    <script>
    function go_to_david() {
        var url = document.getElementsByName("david_url")[0].value;
        var win=window.open(url, '_blank');
        win.focus();
    }
    function go_to_generic_results_ouput() {
        var file_name = document.getElementsByName("results_file")[0].value;
        var url = "viewGenericResultsOutput.php?file="+file_name;
        if (file_name == "")
        {
        	alert("You must compile and run the datapath before viewing results!");
        }
        else
        {
        	window.location.href = url;
        }
    }
    function get_block_positions() {
        var elements = document.getElementsByName("drag_blocks");
        if (elements.length == 0) {
            return "";
        }
        var values = "?";
        for (var i =0; i < elements.length; i++) {
            var style = window.getComputedStyle(elements[i]);
            var top = style.getPropertyValue('top');
            var left = style.getPropertyValue('left');
            var id = elements[i].id;
            values += "&" + id + "="+ top + "," + left;
        }
        return values;
    }
    function compileAndRun()
    {
        //goToGivenPage("compileAndRun.php");
        window.location.href = "compileAndRun.php";
    }
    function connectionsPanel()
    {
        goToGivenPage("connections.php");
    }
    function updateDatapath() {
        goToGivenPage("datapath.php");
    }
    function undoDatapath() {
        window.location.href = "datapath.php";
    }
    function goToTutorial() {
        window.location.href = "https://docs.google.com/document/d/1kES5nI4EXUOtcj9j0D9joEkIpZTgW5hKUAHx0BmjQak/pub"
    }
    function goToGivenPage(pageName) {
        var string = get_block_positions();
        if (string == "") {
            // No blocks are placed.
            window.location.href = pageName;
        }
        else
        {
            var nextPage = "&nextPage="+pageName;
            window.location.href = "updateDatapathFile.php" + string + nextPage;
        }
    }
    
    $(function() {
      var answer;
      var delete_answer;
      $( "#dialog-form" ).dialog({
        autoOpen: false,
        height: 450,
        width: 500,
        modal: true,
        buttons: {
          "Add selected block": function() {
              $("input").each(function(){
                  (this.checked == true) ? answer = $(this).val() : null;
              });
              $( this ).dialog( "close" );
          },
          Cancel: function() {
              answer = null;
              $( this ).dialog( "close" );
          }
          },
          close: function(event, ui) {
              var string = get_block_positions();
              if (answer == "Genomics Portal Block") {
                  // Then we want to go to edit Genomics Portal Block page
                  goToGivenPage("genomicsPortalBlockSetup_step1.php");
              }
              else if (answer == "Intersect Block") {
                  // Then we want to go to the intersect block page
                  goToGivenPage("intersectBlockSetup_step1.php");
              }
              else if (answer == "Generic Results Block") {
                // Then we want to go to the generic results block page
                goToGivenPage("genericResultsBlockSetup_step1.php");
              }
              else if (answer == "DAVID Block") {
                // Then we want to go to the DAVID block page
                goToGivenPage("davidBlockSetup_step1.php");
              }
          }
      });
 
    $( "#add-block-button" )
      .button()
      .click(function() {
        $( "#dialog-form" ).dialog( "open" );
      });
    $( "#delete-dialog-form" ).dialog({
        autoOpen: false,
        height: 450,
        width: 500,
        modal: true,
        buttons: {
          "Delete selected block": function() {
              $("input").each(function(){
                  (this.checked == true) ? delete_answer = $(this).val() : null;
              });
              $( this ).dialog( "close" );
          },
          Cancel: function() {
              delete_answer = null;
              $( this ).dialog( "close" );
          }
          },
          close: function(event, ui) {
              if (delete_answer != null) {
                // if they made a selection then go to the php script to delete the block.
                goToGivenPage("deleteBlock.php?block_id="+delete_answer);
              }
          }
      });
 
    $( "#delete-block-button" )
      .button()
      .click(function() {
        $( "#delete-dialog-form" ).dialog( "open" );
      });
    $('.draggable').draggable({
        containment: '.data_path_wrapper'
    });
  });
  </script>
</head>
<div class="wrapper"> <!-- The wrapper for the page -->
<body>
 
<div id="dialog-form" title="Add Block">
    <p>Select a block that you would like to add to your datapath.</p>
    <form>
    <fieldset>
    <table>
        <?php
        // Here we will dynamically populate the available blocks.
        
        // Create the connnection
        $con = mysqli_connect("127.0.0.1", "root", "UCinci2014", "webbioblocks");
        
        // Check connection
        if (mysqli_connect_errno())
        {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        
        $result = mysqli_query($con, 'SELECT * FROM supported_blocks');
        if ( false===$result ) {
            printf("error: %s\n", mysqli_error($con));
        }
        
        while ($row = mysqli_fetch_assoc($result))
        {
            //echo "<tr><td><input type='radio' name='block_name' class='radio ui-widget-content ui-corner-all' value='".$row['name']."'></td>";
            echo "<tr><td><input type='radio' name='block_name' class='radio_block_name' value='".$row['name']."'></td>";
            echo "<td style='padding: 5px'>".$row['name']."</td><td style='word-wrap: break-word; padding: 5px'>".$row['description']."</td></tr>";
        }
        
        // Free the result
        mysqli_free_result($result);
        
        // Close the connection
        mysqli_close($con);
        ?>
    </table>
  </fieldset>
  </form>
</div>

<div id="delete-dialog-form" title="Delete Block">
    <p>Select a block that you would like to delete from your datapath.</p>
    <form>
        <fieldset>
            <table>
                <?php
                if (isset($_COOKIE['fileID']))
                {
                    $fileID = $_COOKIE['fileID'];
                    $blocks = make_assoc_array_from_file($fileID);
                    if (isset($blocks))
                    {
                        for ($i = 0; $i < count($blocks); $i++)
                        {
                            $blockName = get_block_name($blocks[$i]);
                            echo("<tr><td><input type='radio' name='block_id' class='radio_block_id' value='".$blockName."'></td>");
                            echo("<td style='padding: 5px'>".$blockName."</td></tr>");
                        }
                    }
                    else
                    {
                        // No blocks to delete
                        echo("There are no blocks to delete from the datapath!");
                    }
                }
                else
                {
                    // No blocks to delete
                    echo("There are no blocks to delete from the datapath!");
                }
                ?>
            </table>
        </fieldset>
    </form>
</div>

<!-- This is the code that is displayed when first visiting -->

    <div class='page_header'>
        <div class='page_header_img_right' height="100%">
            <img src='Images\University_of_Cincinnati_logo.png' height="100%"/>
        </div>
        <div class='page_header_img_left' height="100%">
            <img src='Images\webbioblocks_header.png' height="100%" />
        </div>
        <h1 class='page_title'>Welcome to Web Bio Blocks</h1>
    </div>
    <div class="content_wrappter"><!-- The wrapper for the content on the page -->
        <table class="push_buttons_table">
            <tr>
                <td>
                    <input id="add-block-button" class="push_button_left" type="button" value="Add Block" />
                    <input id="delete-block-button" class="push_button_left" type="button" value="Delete Block" />
                </td>
                <td><input class="push_button_right" type="button" value="Tutorial" onclick="goToTutorial()";/></td>
            </tr>
        </table>
        <div class="data_path_wrapper">
                <?php
                    if (isset($_COOKIE['fileID']))
                    {
                        $fileID = $_COOKIE['fileID'];
                        $blocks = make_assoc_array_from_file($fileID);
                        if (isset($blocks))
                        {
                            $blocksToPlace = array();
                            for ($i = 0; $i < count($blocks); $i++)
                            {
                                // Loop through the array of blocks that we have so we can place them.
                                $blockName = get_block_name($blocks[$i]);
                                $blockClassName = get_block_class_name($blocks[$i]);
                                $blockIdName = get_block_id_name($blockName);
                                $x_pos = get_block_x_position($blocks[$i]);
                                $y_pos = get_block_y_position($blocks[$i]);
                                $newBlock = "<div class='draggable ui-draggable ".$blockClassName."' id='".$blockIdName."' name='drag_blocks'";
                                $newBlock .= "style='left: ".$x_pos."; top: ".$y_pos.";' ";
                                if (strcmp($blocks[$i]['block'],'Generic Result') == 0)
                                {
                                    // We have a results block and we want to add a JS function to open up some data. We also need to read in the data
                                    // to an array.
                                    $resultsFile = $blocks[$i]['resultsFile'];
                                    echo("<input type='hidden' name='results_file' value='".$resultsFile."'></input>");
                                    $newBlock .= "ondblclick='go_to_generic_results_ouput()'";
                                }
                                elseif (strcmp($blocks[$i]['block'],'DAVID') == 0)
                                {
                                    // We have a DAVID block and we want to add a JS function to open up the url that was constructed.
                                    $urlFile = $blocks[$i]['dataFile'];
                                    $DAVID_url = get_url_from_file($urlFile);
                                    if ($DAVID_url != "")
                                    {
                                        echo("<input type='hidden' name='david_url' value='".$DAVID_url."'></input>");
                                        $newBlock .= "ondblclick='go_to_david()'";
                                    }
                                }
                                $newBlock .= ">".$blockName."</div>";
                                array_push($blocksToPlace, $newBlock);
                            }
                            foreach($blocksToPlace as $placedBlock)
                            {
                                echo $placedBlock;
                            }
                            if (count($blocksToPlace) > 1)
                            {
                                // More than one block has been placed on the datapath so we should now draw lines (if needed)
                                draw_connector_lines_for_blocks($blocks);
                            }
                            $_SESSION['placedBlocks'] = array();
                            $_SESSION['placedBlocks'] = $blocks;
                            echo("
                                <table style='right: 0; position: absolute; bottom: 0;'>
                                    <tr><td><button type='button' onclick='updateDatapath()'><img width='30px' height='30px' src='Images\checkmark_icon.png'/></button></td>
                                    <td><button type='button' onclick='undoDatapath()'><img width='30px' height='30px' src='Images\cancel_icon.png'/></button></td></tr>
                                </table>
                                 ");
                        }
                        else
                        {
                            clearSession();
                        }
                    }
                ?>
        </div>
        <?php
        if (isset($blocks) && count($blocks) > 0)
        {
            // We have at least one block, display the run button
            echo("
            <table class='push_buttons_table'>
                <tr>
                    <td>
                        <input class='push_button_left' type='button' onclick='compileAndRun()' value='Run' />
                    </td>
                    <td>
                        <input class='push_button_right' type='button' onclick='connectionsPanel()' value='Connections' />
                </tr>
            </table>
            ");
        }
        ?>
    </div>
    <div class='page_footer'>
        <a href='https://docs.google.com/forms/d/1NVWJW4bce8S8Ky6LjhYaRh9UQk4R3sCD85qL--KY7-o/viewform' target='_blank'>
            <img style='float: right; margin-left: auto; margin-right: 5px; margin-top: 10px;' src='Images\bug.png' height='50' width='50'>
        </a>
    </div>
</body>
</div>
</html>
