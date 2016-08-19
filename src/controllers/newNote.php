<?php
  if ( ! defined("SSNS_SCRIPT_INCLUSION") ) die('NO F*CKING DIRECT SCRIPT ACCESS ALLOWED LOL');

  $user = GetCurrentUserOrDie();
  
  if (!empty($_POST)) {
    $fields=array();
    try {
      $fields["text"] = FetchSentData("text", false);
      $fields["tags"] = FetchSentData("tags", true);
      $fields["title"] = FetchSentData("title", false);
      $fields["public"] = FetchSentData("public", true);
      $fields["public"] = $fields["public"] == "1" ? 1 : 0;
    }
    catch (Exception $e) {
      $notifications->pushError($e);
    }
    try { 
      SaveNote($fields);
      $notifications->pushAchievement($lang->GetMessageById(52));
    }
    catch (Exception $e) {
      $notifications->pushError($e);
    }

  }
?>
