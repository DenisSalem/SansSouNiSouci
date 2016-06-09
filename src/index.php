<?php
  if( !file_exists("_config_.php")) {
    header("Location: install.php");
    exit;
  }
  define("SSNS_SCRIPT_INCLUSION",1);
  require_once("core/common.php");
?>
<!DOCTYPE html>
<html>
  <head>
   <link href='style.css' rel='stylesheet' type='text/css'> 
  </head>
  <body>
    <header id="splash">
      <div id="toolBar">
        <?php
          if (isLoggedIn()) { ?>
          <?php }
          else { ?>
            <form>
              <input type="text" value="" placeholder="<?php $lang->EchoMessageById(16); ?>">
              <input type="password" value="" placeholder="<?php $lang->EchoMessageById(17); ?>">
              <input type="submit" value="<?php $lang->EchoMessageById(18); ?>">
            </form>
          <?php } ?>
      </div>
      <h1>SANS SOU NI SOUCI</h1>
    </header>
    <div id="thread">
    </div>
    </div>
  </body>
</html>

