<?php
  if( file_exists("_config_.php")) {
    header("Location: index.php");
    die();
  }
  define("SSNS_SCRIPT_INCLUSION", 1);
  require_once("core/common.php");
  require_once("core/install.php");

  $fields = array();
  $errors = array();
  try {
    if(!empty($_POST)) {
      $fields["dbHost"] = FetchSentData("dbHost",false);
      $fields["dbPort"] = FetchSentData("dbPort",false);
      $fields["dbName"] = FetchSentData("dbName",false);
      $fields["dbUser"] = FetchSentData("dbUser",false);
      $fields["dbPasswd"] = FetchSentData("dbPasswd",false);
      $fields["tablePrefix"] = FetchSentData("tablePrefix",false);
      $fields["adminLogin"] = FetchSentData("adminLogin",false);
      $fields["adminMail"] = FetchSentData("adminMail",false);
      $fields["adminPasswd"] = FetchSentData("adminPasswd",false);
      $fields["adminPasswdRepeat"] = FetchSentData("adminPasswdRepeat",false);
      $fields["dbDriver"] = FetchSentData("dbDriver",false);
    }
  }
  catch (Exception $e) {
    array_push($errors,$lang->GetMessageById(13));
  }

  if(count($errors) == 0) {
  }
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
                <link href='style.css' rel='stylesheet' type='text/css'> 
                <title><?php $lang->echoMessagebyId(0); ?></title>
	</head>
	<body>
		<form id="install" action="install.php" method="post">
		        <h1>SANS SOU NI SOUCI</h1>
		        <h2><?php echo strtoupper($lang->getMessagebyId(0)); ?></h2>
			<?php EchoErrors($errors); ?>
			<label for="dbHost"><?php $lang->echoMessagebyId(1); ?></label>
			<p><input name="dbHost" type="text" value="<?php echo fetchFromArray($fields,"dbHost"); ?>" placeholder="ex: bdd.lolilol.com"></p>
			<label for="dbPort"><?php $lang->echoMessagebyId(2); ?></label>
			<p><input name="dbPort" type="text" value="<?php echo fetchFromArray($fields,"dbPort"); ?>" placeholder="ex: 5432"></p>
			<label for="dbDriver"><?php $lang->echoMessagebyId(3); ?></label>
			<p>
				<select name="DRIVER">
					<option value="pgsql">PostgreSQL</option>
				</select>
			</p>
			<label for="dbName"><?php $lang->echoMessagebyId(4); ?></label>
			<p><input name="dbName" type="text" value="<?php echo fetchFromArray($fields,"dbName"); ?>" placeholder="ex: ssns4life"></p>
			<label for="dbUser"><?php $lang->echoMessagebyId(5); ?></label>
			<p><input name="dbUser" type="text" value="<?php echo fetchFromArray($fields,"dbUser"); ?>" placeholder="ex: Jacque Chirac"></p>
			<label for="dbPasswd"><?php $lang->echoMessagebyId(6); ?></label>
			<p><input name="dbPasswd" type="password" value="<?php echo fetchFromArray($fields,"dbPasswd"); ?>" placeholder="ex: t0p_s3cr3t"></p>
			<label for="tablePrefix"><?php $lang->echoMessagebyId(7); ?></label>
			<p><input name="tablePrefix" type="text" value="<?php echo fetchFromArray($fields,"tablePrefix"); ?>" placeholder="ex: _SSNS_"></p>
			<label for="adminLogin"><?php $lang->echoMessagebyId(8); ?></label>
			<p><input name="adminLogin" type="text" value="<?php echo fetchFromArray($fields,"adminLogin"); ?>" placeholder="ex: helloKitty"></p>
			<label for="adminMail"><?php $lang->echoMessagebyId(9); ?></label>
			<p><input name="adminMail" type="text" value="<?php echo fetchFromArray($fields,"adminMail"); ?>" placeholder="ex: helloKitty@tuxfamily.org"></p>
			<label for="adminPasswd"><?php $lang->echoMessagebyId(10); ?></label>
			<p><input name="adminPasswd" type="password" value="" placeholder="ex: verySecret"></p>
			<label for="adminPasswdRepeat"><?php $lang->echoMessagebyId(11); ?></label>
			<p><input name="adminPasswdRepeat" type="password" value="" placeholder="ex: verySecret"></p>
			<p><input type="submit" name="submit" value="<?php $lang->echoMessagebyId(12); ?>"></p>
		</form>
	</body>
</html>
