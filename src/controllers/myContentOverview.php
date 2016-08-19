<?php
  if ( ! defined("SSNS_SCRIPT_INCLUSION") ) die('NO F*CKING DIRECT SCRIPT ACCESS ALLOWED LOL');
  
  $user = GetCurrentUserOrDie();

  require_once("controllers/clusters.php");

  $clusters = new Clusters(array()); 
?>
