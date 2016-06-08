<?php
  if( !file_exists("_config_.php")) {
    header("Location: install.php");
    exit;
  }
  define("_INCLUDED_",0);
  require_once("core/common.php");
?>
<!DOCTYPE html>
<html>
  <head>
   <link href='https://fonts.googleapis.com/css?family=Amatic+SC' rel='stylesheet' type='text/css'> 
   <link href='style.css' rel='stylesheet' type='text/css'> 
  </head>
  <body>
    <header id="splash">
      <form>
        <h1>SANS SOU NI SOUCI</h1>
        <img src="gfx/separationBar.gif">
      </form>
    </header>
    <div id="thread">
    </div>
    </div>
  </body>
</html>

