<?php
  if ( ! defined("SSNS_SCRIPT_INCLUSION") ) die('NO F*CKING DIRECT SCRIPT ACCESS ALLOWED LOL');
  require_once("controllers/profil.php");
?>
<div id="profil">
  <?php
      $notifications->Display();
  ?>
  <div id="avatar">
    <?php
      if($user->avatar != "") { ?>
        <img src="avatars/<?php echo $user->avatar; ?>" alt="" title="" />
        <?php 
      }
    ?>
  </div>
  <form action="index.php?page=profil" method="post" enctype="multipart/form-data">
  <ul id="profilDetails">
    <li>
      <label for="nick"><?php $lang->EchoMessageById(22); ?></label><input type="text" name="nick" value="<?php echo $user->nick; ?>" placeholder="<?php $lang->EchoMessageById(27); ?>" />
    </li>
    <li>
      <label for="mail"><?php $lang->EchoMessageById(23); ?></label><input type="text" name="mail" value="<?php echo $user->mail; ?>" placeholder="<?php $lang->EchoMessageById(28); ?>" />
    </li>
    <li>
      <label for="password"><?php $lang->EchoMessageById(17); ?></label><input type="password" name="password" value="" placeholder="" />
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
  </form>
  <form id="deleteAvatar" action="index.php?page=profil" method="post">
    <input type="submit" value="<?php $lang->EchoMessageById(37); ?>" name="deleteAvatar">
  </form>
</div>
