<!DOCTYPE html>
<?php
    include 'clearSession.php';
    include 'functions.php';
    session_start();
?>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <title>Web Bio Blocks</title>
    <link rel="stylesheet" type="text/css" href="main.css">
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
    function go_to_generic_results_ouput() {
        var file_name = document.getElementsByName("results_alert")[0].value;
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
        goToGivenPage("compileAndRun.php");
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
          }
      });
 
    $( "#add-block-button" )
      .button()
      .click(function() {
        $( "#dialog-form" ).dialog( "open" );
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
    <p>Select a block that you would like to add to your data flow.</p>
    <form>
    <fieldset>
    <table>
        <?php
        // Here we will dynamically populate the available blocks.
        
        // Create the connnection
        $con = mysqli_connect("127.0.0.1", "root", "Hockey101", "webbioblocks");
        
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

<!-- This is the code that is displayed when first visiting -->
    <div class="page_header">
        <img style="float: right; margin-left: auto; margin-right: 5px;" src="Images\help_icon.png" />
        <h1 class="page_title">Welcome to Web Bio Blocks</h1>
    </div>
    <div class="content_wrappter"><!-- The wrapper for the content on the page -->
        <table class="push_buttons_table">
            <tr>
                <td>
                    <input id="add-block-button" class="push_button_left" type="button" value="Add Block" />
                    <input class="push_button_left" type="button" value="Delete Block" />
                </td>
                <td><input class="push_button_right" type="button" value="Tutorial" /></td>
            </tr>
        </table>
        <div class="data_path_wrapper">
                <?php
                    $fileID = $_SESSION['fileID'];
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
                                $resultsFile = $block['resultsFile'];
                                echo("<input type='hidden' name='results_file' value='".$resultsFile."'></input>");
                                $newBlock .= "ondblclick='go_to_generic_results_ouput()'";
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
                    }
                    else
                    {
                        clearSession();
                    }
                ?>
        </div>
        <?php
        if (isset($_SESSION['placedBlocks']))
        {
            // We have at least one block, display the run button
            echo("
            <table class='push_buttons_table'>
                <tr>
                    <td>
                        <input id='add-block-button' class='push_button_left' type='button' onclick='compileAndRun()' value='Run' />
                    </td<
                </tr>
            </table>
            ");
        }
        ?>
    </div>
</body>
</div>
</html>
