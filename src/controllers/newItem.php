<?php
  if ( ! defined("SSNS_SCRIPT_INCLUSION") ) die('NO F*CKING DIRECT SCRIPT ACCESS ALLOWED LOL');

  $user = GetUserOrDie($_SESSION["userid"]);

  $errors = array();

  if (!empty($_POST)) {
    $fields=array();
    try {
      $fields["description"] = FetchSentData("description", false);
      $fields["public"] = FetchSentData("public", true);
      $fields["public"] = $fields["public"] == "1" ? true : false;
    }
    catch (Exception $e) {
      print($e);
      $notifications->pushError($lang->GetMessageById(41));
    }

    try {
      $fields["img"] = SaveImageIfExists();
    }
    catch (Exception $e) {
      $notifications->pushError($lang->GetMessageById(36));
    }
    try { 
      SaveItem($fields);
      $notifications->pushAchievement($lang->GetMessageById(45));
    }
    catch (Exception $e) {
      $notifications->pushError($e);
    }
  }

?>
