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
    }
    catch (Exception $e) {
      array_push($errors, $lang->GetMessageById(30));
    }

    $fields["password"] = FetchSentData("password", true);
    $fields["passwordVerify"] = FetchSentData("passwordVerify", true);
    $fields["about"] = FetchSentData("about",true);
   
    if (ValueAlreadyExists($TABLE_PREFIX."users","nick",$fields["nick"],$_SESSION["userid"])) {
      array_push($errors, $lang->GetMessageById(31));
    }

    if (ValueAlreadyExists($TABLE_PREFIX."users","mail",$fields["mail"],$_SESSION["userid"])) {
      array_push($errors, $lang->GetMessageById(32));
    }

    if (count($errors) == 0) {
      $error = updateUserProfil($_SESSION["userid"], $fields);
    }
    if (count($errors) == 0) {
      $user = getUser($_SESSION["userid"]);
    }
  }
?>
