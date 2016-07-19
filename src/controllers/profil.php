<?php
  if ( ! defined("SSNS_SCRIPT_INCLUSION") ) die('NO F*CKING DIRECT SCRIPT ACCESS ALLOWED LOL');
  
  $user = GetUserOrDie($_SESSION["userid"]);

  if ($user === NULL) die('');

  $errors = array();
  
  if (!empty($_POST)) {
    if (isset($_POST["deleteAvatar"])) {
      DeleteAvatar();
      $user = GetUser($_SESSION["userid"]);
    }
    else {
      $fields=array();
    
      try {
        $fields["nick"] = FetchSentData("nick", false);
        $fields["mail"] = FetchSentData("mail", false);
        $fields["password"] = FetchSentData("password", true);
        $fields["passwordVerify"] = FetchSentData("passwordVerify", true);
        $fields["about"] = FetchSentData("about",true);
      }

      catch (Exception $e) {
        $notifications->pushError($lang->GetMessageById(30));
      }

      if ($notifications->NoErrors()) {
        updateUserProfil($_SESSION["userid"], $fields);
      }

      if (count($errors) == 0) {
        $user = GetUser($_SESSION["userid"]);
      }
    }
  }
?>
