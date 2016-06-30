<?php
  if ( ! defined("SSNS_SCRIPT_INCLUSION") ) die('NO F*CKING DIRECT SCRIPT ACCESS ALLOWED LOL');
  
  try {
    $user = getUser($_SESSION["userid"]);
  }
  catch (Exception $e) {
    die('');
  }

  if ($user === NULL) die('');

  $errors = array();
  
  if (!empty($_POST)) {
    
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
      $user = getUser($_SESSION["userid"]);
    }
  }
?>
