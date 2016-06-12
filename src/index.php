<?php
  if( !file_exists("_config_.php")) {
    header("Location: install.php");
    exit;
  }
  define("SSNS_SCRIPT_INCLUSION",1);
  require_once("core/common.php");
  require_once("_config_.php");
  if (isset($_POST["nick"]) && isset($_POST["password"]) && !isLoggedIn()) {
    login();
  }
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
            <form method="POST" action"index.php">
              <input type="text" name="nick" value="" placeholder="<?php $lang->EchoMessageById(16); ?>">
              <input type="password" name="password" value="" placeholder="<?php $lang->EchoMessageById(17); ?>">
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

