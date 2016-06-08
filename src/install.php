<?php
  if( file_exists("_config_.php")) {
    header("Location: index.php");
    exit;
  }
  define("_INCLUDED_",0);
  require_once("core/common.php");
  require_once("core/install.php");

  $ADMIN_LOGIN = "";
  $ADMIN_MAIL = "";
  $ADMIN_PASSWORD = "";
  $ADMIN_PASSWORD_REPEAT = "";
  $BDD_HOST = "";
  $BDD_PORT = "";
  $BDD_NAME = "";
  $BDD_USERNAME = "";
  $BDD_PASSWORD = "";
  $DRIVER = "";
  $TABLE_PREFIX = "_SSNS_";
  $errors = "";
  $noError = true;

  if(
    isset($_POST["ADMIN_MAIL"]) && 
    isset($_POST["BDD_PORT"]) && 
    isset($_POST["ADMIN_PASSWORD_REPEAT"]) && 
    isset($_POST["ADMIN_PASSWORD"]) && 
    isset($_POST["ADMIN_LOGIN"]) && 
    isset($_POST["BDD_HOST"]) && 
    isset($_POST["BDD_NAME"]) && 
    isset($_POST["BDD_USERNAME"]) && 
    isset($_POST["BDD_PASSWORD"]) && 
    isset($_POST["TABLE_PREFIX"])
  ) {

    $BDD_HOST = $_POST["BDD_HOST"];
    $BDD_NAME = $_POST["BDD_NAME"];
    $BDD_PORT = $_POST["BDD_PORT"];
    $BDD_USERNAME = $_POST["BDD_USERNAME"];
    $BDD_PASSWORD = $_POST["BDD_PASSWORD"];
    $TABLE_PREFIX = $_POST["TABLE_PREFIX"];
    $ADMIN_LOGIN = $_POST["ADMIN_LOGIN"];
    $ADMIN_MAIL = $_POST["ADMIN_MAIL"];
    $ADMIN_PASSWORD = $_POST["ADMIN_PASSWORD"];
    $ADMIN_PASSWORD_REPEAT = $_POST["ADMIN_PASSWORD_REPEAT"];
    $DRIVER = $_POST['DRIVER'];

    /* Tous les champs sont ils remplis?! */
    if (empty($_POST["BDD_HOST"])) {
      $errors .= "<p class=\"error\">".$lang->getMessageById(13)."<?php ?></p>";
      $noError = false;
    }
    if (empty($_POST["BDD_PORT"])) {
      $errors .= "<p class=\"error\">".$lang->getMessageById(14)."</p>";
      $noError = false;
    }
    if (empty($_POST["BDD_NAME"])) {
      $errors .= "<p class=\"error\">".$lang->getMessageById(15)."</p>";
      $noError = false;
    }
    if (empty($_POST["BDD_USERNAME"])) {
      $errors .= "<p class=\"error\">".$lang->getMessageById(16)."</p>";
      $noError = false;
    }
    if (empty($_POST["BDD_PASSWORD"])) {
      $errors .= "<p class=\"error\">".$lang->getMessageById(17)."</p>";
      $noError = false;
    }
    if (empty($_POST["TABLE_PREFIX"])) {
      $errors .= "<p class=\"error\">".$lang->getMessageById(18)."</p>";
      $noError = false;
    }
    if (empty($_POST["ADMIN_LOGIN"])) {
      $errors .= "<p class=\"error\">".$lang->getMessageById(19)."</p>";
      $noError = false;
    }
    if (empty($_POST["ADMIN_PASSWORD"])) {
      $errors .= "<p class=\"error\">".$lang->getMessageById(20)."</p>";
      $noError = false;
    }
    if (empty($_POST["ADMIN_PASSWORD_REPEAT"])) {
      $errors .= "<p class=\"error\">".$lang->getMessageById(21)."</p>";
      $noError = false;
    }
    if (empty($_POST["ADMIN_MAIL"])) {
      $errors .= "<p class=\"error\">".$lang->getMessageById(22)."</p>";
      $noError = false;
    }
    else if (substr(phpversion(),0,3) >= 5.2 && filter_var($_POST["ADMIN_MAIL"], FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE) == null) {
      $errors .= "<p class=\"error\">".$lang->getMessageById(23)."</p>";
      $noError = false;
    }
    if (empty($_POST["DRIVER"])) {
      $errors .= "<p class=\"error\">".$lang->getMessageById(24)."</p>";
      $noError = false;
    }
    if (!empty($_POST["ADMIN_PASSWORD_REPEAT"]) && !empty($_POST["ADMIN_PASSWORD"]) && $_POST["ADMIN_PASSWORD_REPEAT"] != $_POST["ADMIN_PASSWORD"]) {
      $errors .= "<p class=\"error\">".$lang->getMessageById(25)."</p>";
      $noError = false;
    }
    if ($noError == true) {
      try {
        if ($DRIVER == "pgsql") {
          $install = new installPGSQL(
            $dbHost,
            $dbPort,
            $dbName,
            $dbUser,
            $dbPasswd,
            $tablePrefix
          );
        }
        else {
          throw new ErrorException("Wrong DB", 133);
        }
        $install->CreateAdminAccount(
          $adminLogin,
          $adminPasswd,
          $adminMail
        );
        try {
          $install->SetConfigurationFile();
        }
        catch(Exception $e) {
          $errors .= "<p class=\"error\">".."</p>";
        }
      }
      catch(Exception $e) {
        if ($e->getCode() == 129) {
          $errors .= "<p class=\"error\">".$lang->getMessageById(26)."</p>";
        }
        else if ($e->getCode() == 132) {
          $errors .="<p class=\"error\">".$lang->getMessageById(27)."</p>";
        }
        else if ($e->getCode() == 131) {
          $errors .="<p class=\"error\">".$lang->getMessageById(28)."</p>";
        }
        else if ($e->getCode() == 130) {
          $errors .="<p class=\"error\">".$lang->getMessageById(29)."</p>";
        }
        else if ($e->getCode() != 129 && $e->getCode() != 130 && $e->getCode() != 131 && $e->getCode() != 132) {
          $errors .= "<p class=\"error\">MESSAGE :".$e->getMessage()."</p>";
          $errors .= "<p class=\"error\">LINE : ".$e->getLine()."</p>";
          $errors .= "<p class=\"error\">TRACE :".$e->getTraceAsString()."</p>";
          $errors .= "<p class=\"error\">CODE :".$e->getCode()."</p>";
          $errors .= "<p class=\"error\">FILE :".$e->getFile()."</p>";
        }
        if ($e->getCode() == 133) {
          $errors .= "<p class=\"error\">".$lang->getMessageById(12)."</p>";
        }
      }
      if (file_exists("_config_.php"))  {
            /*require_once("_common_.php");
            session_start();
            $_SESSION["userId"] = 1;
            $_SESSION["isAdmin"] = true;
            header("Location: index.php");
            exit;*/
      }
    }
  }
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
                <link href='https://fonts.googleapis.com/css?family=Loved+by+the+King' rel='stylesheet' type='text/css'> 
                <link href='style.css' rel='stylesheet' type='text/css'> 
                <title><?php $lang->echoMessagebyId(0); ?></title>
	</head>
	<body>
		<form id="install" action="install.php" method="post">
		        <h1>SANS SOU NI SOUCI</h1>
		        <h2><?php echo strtoupper($lang->getMessagebyId(0)); ?></h2>
        <img src="gfx/separationBar.gif">
			<?php echo $errors; ?>
			<label for="BDD_HOST"><?php $lang->echoMessagebyId(1); ?></label>
			<p><input name="BDD_HOST" type="text" value="<?php echo $BDD_HOST; ?>" placeholder="ex: bdd.lolilol.com"></p>
			<label for="BDD_PORT"><?php $lang->echoMessagebyId(2); ?></label>
			<p><input name="BDD_PORT" type="text" value="<?php echo $BDD_PORT; ?>" placeholder="ex: 5432"></p>
			<label for="DRIVER"><?php $lang->echoMessagebyId(3); ?></label>
			<p>
				<select name="DRIVER">
					<option value="pgsql">PostgreSQL</option>
				</select>
			</p>
			<label for="BDD_NAME"><?php $lang->echoMessagebyId(4); ?></label>
			<p><input name="BDD_NAME" type="text" value="<?php echo $BDD_NAME; ?>" placeholder="ex: ssns4life"></p>
			<label for="BDD_USERNAME"><?php $lang->echoMessagebyId(5); ?></label>
			<p><input name="BDD_USERNAME" type="text" value="<?php echo $BDD_USERNAME; ?>" placeholder="ex: Jacque Chirac"></p>
			<label for="BDD_PASSWORD"><?php $lang->echoMessagebyId(6); ?></label>
			<p><input name="BDD_PASSWORD" type="password" value="<?php echo $BDD_PASSWORD; ?>" placeholder="ex: t0p_s3cr3t"></p>
			<label for="TABLE_PREFIX"><?php $lang->echoMessagebyId(7); ?></label>
			<p><input name="TABLE_PREFIX" type="text" value="<?php echo $TABLE_PREFIX; ?>" placeholder="ex: _GSEL_"></p>
			<label for="ADMIN_LOGIN"><?php $lang->echoMessagebyId(8); ?></label>
			<p><input name="ADMIN_LOGIN" type="text" value="<?php echo $ADMIN_LOGIN; ?>" placeholder="ex: helloKitty"></p>
			<label for="ADMIN_MAIL"><?php $lang->echoMessagebyId(9); ?></label>
			<p><input name="ADMIN_MAIL" type="text" value="<?php echo $ADMIN_MAIL; ?>" placeholder="ex: helloKitty@tuxfamily.org"></p>
			<label for="ADMIN_PASSWORD"><?php $lang->echoMessagebyId(10); ?></label>
			<p><input name="ADMIN_PASSWORD" type="password" value="" placeholder="ex: verySecret"></p>
			<label for="ADMIN_PASSWORD_REPEAT"><?php $lang->echoMessagebyId(11); ?></label>
			<p><input name="ADMIN_PASSWORD_REPEAT" type="password" value="" placeholder="ex: verySecret"></p>
			<p><input type="submit" name="submit" value="<?php $lang->echoMessagebyId(12); ?>"></p>
		</form>
	</body>
</html>
