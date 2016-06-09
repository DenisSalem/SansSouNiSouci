<?php
  if ( ! defined("SSNS_SCRIPT_INCLUSION") ) die('NO F*CKING DIRECT SCRIPT ACCESS ALLOWED LOL');

  require_once("core.php");

  set_error_handler("__ERROR_HANDLER__");

  date_default_timezone_set("Europe/Paris");

  $lang = new LanguageHandler();
?>
