<?php 
session_start();
require_once("./useful_functions.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
  <?php require_once("./templates/head.html") ?>
<body>
  <?php show_flash_message(); ?>

  <h1>Sir Galahad</h1>
  <p>Seja bem-vindo</p>
  <?php require_once("./templates/navigation_bar.html") ?>
</body>
</html>