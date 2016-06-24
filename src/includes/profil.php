<?php
  if ( ! defined("SSNS_SCRIPT_INCLUSION") ) die('NO F*CKING DIRECT SCRIPT ACCESS ALLOWED LOL');
  
  try {
    $user = getUser($_SESSION["userid"]);
  }
  catch (Exception $e) {
    die('');
  }
  if ($user === NULL) die('');
?>
<div id="profil">
  <div id="avatar">
    <img src="<?php echo $user->avatar; ?>" alt="" title="" />
  </div>
  <form action="index.php?page=profil" method="post">
  <ul id="profilDetails">
    <li>
      <label for="nick"><?php $lang->EchoMessageById(22); ?></label><input type="text" name="nick" value="<?php echo $user->nick; ?>" placeholder="<?php $lang->EchoMessageById(27); ?>" />
    </li>
    <li>
      <label for="email"><?php $lang->EchoMessageById(23); ?></label><input type="text" name="email" value="<?php echo $user->mail; ?>" placeholder="<?php $lang->EchoMessageById(28); ?>" />
    </li>
    <li>
      <label for="password"><?php $lang->EchoMessageById(17); ?></label><input type="password" value="" placeholder="" />
    </li>
    <li>
      <label for="passwordVerify"><?php $lang->EchoMessageById(26); ?></label><input type="password" name="passwordVerify" value="" placeholder="" />
    </li>
    <li>
      <label for="avatar"><?php $lang->EchoMessageById(24); ?></label><input type="file" name="avatar" value="" placeholder="" />
    </li>
    <li>
      <label for="about"><?php $lang->EchoMessageById(25); ?></about>
      <textarea name="about"><?php echo $user->about; ?></textarea>
    </li>
    <li>
      <input type="submit" value="<?php $lang->EchoMessageById(29); ?>" />
    </li>
  </ul>
  <form>
</div>
