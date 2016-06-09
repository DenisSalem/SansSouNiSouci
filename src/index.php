<?php
  if( !file_exists("_config_.php")) {
    header("Location: install.php");
    exit;
  }
  define("SSNS_SCRIPT_INCLUDED",0);
  require_once("core/common.php");
?>
<!DOCTYPE html>
<html>
  <head>
   <link href='style.css' rel='stylesheet' type='text/css'> 
  </head>
  <body>
    <header id="splash">
      <h1>SANS SOU NI SOUCI</h1>
    </header>
    <div id="thread">
    </div>
    </div>
  </body>
</html>

