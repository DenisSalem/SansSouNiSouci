<?php
  if ( ! defined("SSNS_SCRIPT_INCLUSION") ) die('NO F*CKING DIRECT SCRIPT ACCESS ALLOWED LOL');
  require_once("controllers/newItem.php");
?>
<div id="newItem">
  <h2><?php $lang->EchoMessageById(20); ?></h2>
  <?php
      $notifications->Display();
  ?>
  <form action="index.php?page=newItem" method="post" enctype="multipart/form-data">
    <ul>
      <li>
        <label for="description"><?php $lang->EchoMessageById(25); ?></about>
        <textarea name="description"></textarea>
      </li>
    <li>
      <label for="img"><?php $lang->EchoMessageById(39); ?></label><input type="file" name="img" value="" placeholder="" />
    </li>
    <li>
      <label for="public">
        <?php $lang->EchoMessageById(40); ?>
      </label>
      <select name="public">
        <option value="1">
          <?php $lang->EchoMessageById(43); ?>
        </option>
        <option value="0">
          <?php $lang->EchoMessageById(44); ?>
        </option>
      </select>
    </li>
    <li>
      <input type="submit" value="<?php $lang->EchoMessageById(42); ?>" />
    </li>
    </ul>
  </form>
</div>
