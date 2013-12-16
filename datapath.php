<!DOCTYPE html>

<html lang="en-US">
<head>
    <meta charset="utf-8">
    <title>Web Bio Blocks</title>
    <link rel="stylesheet" type="text/css" href="main.css">     
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
  $(function() {
 
    $( "#dialog-form" ).dialog({
      autoOpen: false,
      height: 300,
      width: 350,
      modal: true,
      buttons: {
        "Add selected block": function() {
            $( this ).dialog( "close" );
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      },
      close: function() {
      }
    });
 
    $( "#add-block-button" )
      .button()
      .click(function() {
        $( "#dialog-form" ).dialog( "open" );
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
            echo "<tr><td><input type='radio' name='block_name' class='radio ui-widget-content ui-corner-all' value='".$row['name']."'></td>";
            echo "<td>".$row['name']."</td><td style='word-wrap: break-word'>".$row['description']."</td></tr>";
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
            <canvas>
            </canvas>
        </div>
    </div>
</body>
</div>
</html>
