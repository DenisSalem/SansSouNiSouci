<?php
  if ( ! defined("SSNS_SCRIPT_INCLUSION") ) die('NO F*CKING DIRECT SCRIPT ACCESS ALLOWED LOL');

  class Notifications {
    private $errors;
    private $achievements;

    function __construct() {
      $this->errors = array();
      $this->achievements = array();
    }

    function PushError($message) {
      array_push($this->errors, $message);
    }

    function PushAchievement($message) {
      array_push($this->achievements, $message);
    }

    function Display() {
      foreach($this->errors as $value) {
        echo "<p class=\"error\">".$value."</p>\n";
      }
      foreach($this->achievements as $value) {
        echo "<p class=\"achievement\">".$value."</p>\n";
      }
    }

    function NoErrors() {
      if (count($this->errors) == 0) {
        return True;
      }
      return false;
    }
  }

  class LanguageHandler {
    private $lang;

    function __construct() {
      $http_accept_language_array = explode(",",$_SERVER["HTTP_ACCEPT_LANGUAGE"]);
      if ("fr" == $http_accept_language_array[0]) {
        $this->lang = "fr";
      }
      else {
        $this->lang = "fr";
      }
    }
    
    private function GetLine($id) {
      $messages = fopen("Messages/".$this->lang,'r');
      $i = 0;
      while (($line = fgets($messages)) !== false) {
        if($i == $id) {
          return $line;
        }
        $i++;
      }
    }

    public function GetMessageById($messageId) {
      return $this->GetLine($messageId);
    }

    public function EchoMessageById($messageId) {
      echo $this->GetLine($messageId);
    }
  }

  function __ERROR_HANDLER__($errno, $errstr, $errfile, $errline, $errcontext) {
    if (0 === error_reporting()) {
      return false;
    }	

    throw new ErrorException($errstr, $errno, $errno, $errfile, $errline);
  };

  //Prevent undefined error
  function FetchFromArray($array, $index) {
    if (isset($array[$index])) {
      return $array[$index];
    }
    else {
      return NULL;
    }
  }

  function FetchSentData($index, $allowEmpty=true) {
    if(isset($_POST[$index])) {
      if(!$allowEmpty && empty($_POST[$index])) {
        Throw new ErrorException("Empty data", 255);
      }
      return $_POST[$index];
    }
    if(isset($_GET[$index])) {
      if(!$allowEmpty && empty($_GET[$index])) {
        Throw new ErrorException("Empty data", 255);
      }
      return $_GET[$index];
    }
    Throw new ErrorException("Undefined Index", 255);
  }

  function isLoggedIn() {
    if (isset($_SESSION["userid"])) {
      return true;
    }
    return false;
  }

  function login() {
    global $dbDriver;
    global $TABLE_PREFIX;
    $QueryResult = $dbDriver->PrepareAndExecute(
      "SELECT * FROM ".$TABLE_PREFIX."users WHERE nick=$1 AND password=$2",
      array(
        $_POST["nick"],
        hash('sha512',$_POST["password"])
      )
    );
    if ($QueryResult->CountRow() == 1) {
      $_SESSION["userid"] = $QueryResult->get()->id;
      return true;
    }
    return false;

  }

  function GetUser($id) {
    global $dbDriver;
    global $TABLE_PREFIX;
    $QueryResult = $dbDriver->PrepareAndExecute(
      "SELECT * FROM ".$TABLE_PREFIX."users WHERE id=$1",
      array(
        $id
      )
    );
    if ($QueryResult->CountRow() == 1) {
      return $QueryResult->get();
    }
    return NULL;
  }

  function GetUserOrDie($id) {
    try {
      return GetUser($id);
    }
    catch (Exception $e) {
      die('');
    }
  }

  function ValueAlreadyExists($table, $field, $value,$originID) {
    global $dbDriver;
    
    $QueryResult = $dbDriver->PrepareAndExecute(
      "SELECT ".$field." FROM ".$table." WHERE ".$field." = $1 AND id != $2",
      array(
        $value,
        $originID
      )
    );

    if($QueryResult->CountRow() >= 1) {
      return true;
    }
    return false;
  }

  function UpdateUserProfil($id, $fields) {
    global $dbDriver;
    global $TABLE_PREFIX;
    global $notifications;
    global $lang;

    if (ValueAlreadyExists($TABLE_PREFIX."users","nick",$fields["nick"],$_SESSION["userid"])) {
      $notifications->PushError($lang->GetMessageById(31));
    }

    if (ValueAlreadyExists($TABLE_PREFIX."users","mail",$fields["mail"],$_SESSION["userid"])) {
      $notifications->PushError($lang->GetMessageById(32));

    }

    if( (!empty($fields["password"]) || !empty($fields["passwordVerify"])) && $fields["password"] != $fields["passwordVerify"]) {
      $notifications->PushError($lang->GetMessageById(33));
    }

    if(!empty($_FILES["avatar"]["tmp_name"])) {
      $avatar = MakeAvatar($_FILES["avatar"]["tmp_name"]);
      if ($notifications->NoErrors()) {
        $dbDriver->PrepareAndExecute(
          "UPDATE ".$TABLE_PREFIX."users SET avatar=$1 WHERE id=$2",
          array(
            $avatar,
            $_SESSION["userid"]
          )
        );
      }
    }

 
    if ($notifications->NoErrors()) {
      $dbDriver->PrepareAndExecute(
        "UPDATE ".$TABLE_PREFIX."users SET nick=$1, mail=$2,about=$3 WHERE id=$4",
        array(
          $fields["nick"],
          $fields["mail"],
          $fields["about"],
          $_SESSION["userid"]
        )
      );

      if(!empty($fields["password"]) && !empty($fields["passwordVerify"])) {
        $dbDriver->PrepareAndExecute(
          "UPDATE ".$TABLE_PREFIX."users SET password=$1 WHERE id = $2",
          array(
            hash('sha512', $fields["password"]),
            $_SESSION["userid"]
          )
        );
      }
      $notifications->PushAchievement($lang->GetMessageById(34));
    }

  }
  // http://stackoverflow.com/questions/8978566/change-image-size-php
  // http://stackoverflow.com/questions/6066951/php-image-type-detection

  function IsImage($filename) {
    $extensions = array(IMAGETYPE_PNG => ".png", IMAGETYPE_JPEG => ".jpg"); 
    $exifType = exif_imagetype($filename);
    if ( !isset($extensions[$exifType])) {
      return null;
    }
    return $exifType;
  }

  function SaveImageIfExists() {
    if(!empty($_FILES["img"]["tmp_name"])) {
      $exifType = IsImage($_FILES["img"]["tmp_name"]);
      $extensions = array(IMAGETYPE_PNG => ".png", IMAGETYPE_JPEG => ".jpg"); 
      $itemFilename = hash('md5',$_SESSION["userid"].$_FILES["img"]["tmp_name"]).$extensions[$exifType];
      copy($_FILES["img"]["tmp_name"], "items/".$itemFilename);
      return $itemFilename;
    }
    return "";
  }

  function MakeAvatar($filename) {
    global $notifications;
    global $lang;

    $exifType = IsImage($filename);
    if ( empty($exifType) ) {
      $notifications->PushError($lang->GetMessageById(36));
      return;
    }
    
    $original_info = getimagesize($filename);
    $original_w = $original_info[0];
    $original_h = $original_info[1];

    if($original_w > $original_h) {
      $avatar_h = 100;
      $avatar_w = ($original_w / $original_h) * 100;
    }
    else {
      $avatar_w = 100;
      $avatar_h = ($original_h / $original_w) * 100;
    }

    if($exifType == IMAGETYPE_PNG) {
      $original_img = imagecreatefrompng($filename);
    }
    else if($exifType == IMAGETYPE_JPEG) {
      $original_img = imagecreatefromjpeg($filename);
    }

    $avatar_img = imagecreatetruecolor($avatar_w, $avatar_h);
    imagecopyresampled($avatar_img, $original_img,
      0, 0,
      0, 0,
      $avatar_w, $avatar_h,
      $original_w, $original_h
    );

    $avatarFilename = hash('md5',$_SESSION["userid"].$_FILES["avatar"]["tmp_name"]).".png";
    imagepng($avatar_img, "avatars/".$avatarFilename);
    imagedestroy($avatar_img);
    imagedestroy($original_img);

    return $avatarFilename;
  }

  function DeleteAvatar() {
    global $user;
    global $dbDriver;
    global $TABLE_PREFIX;
    unlink("avatars/".$user->avatar);
    $dbDriver->PrepareAndExecute(
      "UPDATE ".$TABLE_PREFIX."users set avatar='' WHERE id=$1",
      array(
        $_SESSION["userid"]
      )
    );
  }

  function SaveItem($fields) {
    global $dbDriver;
    global $TABLE_PREFIX;

    $cluster = GetLastCluster();
    if (empty($cluster)) {
      $dbDriver->Execute(
        "INSERT INTO ".$TABLE_PREFIX."clusters(type) VALUES(false)"
      );
      $cluster = GetLastCluster();
    }

    $dbDriver->PrepareAndExecute(
      "INSERT INTO ".$TABLE_PREFIX."items(idowner, idcluster, public, description, img, date) VALUES($1, $2, $3, $4, $5, $6)",
      array(
        $_SESSION["userid"],
        $cluster->id,
        $fields["public"],
        $fields["description"],
        $fields["img"],
        date("Y-m-d H:i:s")
      )
    );
  }

  function GetLastCluster() {
    global $dbDriver;
    global $TABLE_PREFIX;

    $QueryResult = $dbDriver->execute(
      "SELECT * FROM ".$TABLE_PREFIX."clusters ORDER BY id DESC LIMIT 1"
    );
    if($QueryResult->CountRow() < 1) {
      return null;
    }
    else {
      return $QueryResult->get();
    }
  }

  require_once("databaseDrivers.php");
?>
