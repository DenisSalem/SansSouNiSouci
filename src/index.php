<?php
  if( !file_exists("_config_.php")) {
    header("Location: install.php");
    exit;
  }
  define("SSNS_SCRIPT_INCLUSION",1);
  require_once("core/common.php");
  require_once("_config_.php");
  try {
    session_start();
  }
  catch(Exception $e) {
  }
  if (isset($_POST["nick"]) && isset($_POST["password"]) && !isLoggedIn()) {
    login();
  }
  else if (isset($_GET["page"]) && $_GET["page"] == "logoff") {
    session_destroy();
    header("Location: index.php");
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <title>SANS SOU NI SOUCI</title>
   <link href='style.css' rel='stylesheet' type='text/css'> 
  </head>
  <body>
    <header id="splash">
      <ul id="toolBar">
        <?php
          if (isLoggedIn()) { ?>
              <li><a href="?page=logoff"><?php $lang->EchoMessageById(19); ?></a></li>
              <li><a href="?page=newItem"><?php $lang->EchoMessageById(20); ?></a></li>
              <li><a href="?page=profil"><?php $lang->EchoMessageById(21); ?></a></li>
          <?php }
          else { ?>
            <form method="POST" action"index.php">
              <input type="text" name="nick" value="" placeholder="<?php $lang->EchoMessageById(16); ?>">
              <input type="password" name="password" value="" placeholder="<?php $lang->EchoMessageById(17); ?>">
              <input type="submit" value="<?php $lang->EchoMessageById(18); ?>">
            </form>
          <?php } ?>
      </ul>
      <h1>SANS SOU NI SOUCI</h1>
    </header>
    <?php
      $pages = array(
        "newItem",
        "profil"
      );
      if(isset($_GET["page"]) && in_array($_GET["page"], $pages, true)) {
        require_once("includes/".$_GET["page"].".php");
      }
    ?>
  </body>
</html>

