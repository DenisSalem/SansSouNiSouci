<?php
  if ( ! defined("SSNS_SCRIPT_INCLUSION") ) die('NO F*CKING DIRECT SCRIPT ACCESS ALLOWED LOL');
  require_once("controllers/newNote.php");
?>
<div id="newNote">
  <h2><?php $lang->EchoMessageById(48); ?></h2>
  <?php
      $notifications->Display();
  ?>
  <form action="index.php?page=newNote" method="post" enctype="multipart/form-data">
    <ul>
      <li>
        <label for="title"><?php $lang->EchoMessageById(50); ?></label>
        <input name="title" type="text" value="" />
      </li>
      <li>
        <label for="text"><?php $lang->EchoMessageById(49); ?></about>
        <textarea name="text"></textarea>
      </li>
      <li>
        <label for="tags"><?php $lang->EchoMessageById(46); ?></label>
        <input name="tags" type="text" value="" placeholder="<?php $lang->EchoMessageById(47); ?>" />
      </li>
      <li>
        <label for="public"><?php $lang->EchoMessageById(51); ?></label>
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
