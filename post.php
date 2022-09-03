<?php
  require_once("./useful_functions.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
  <?php require_once("./templates/head.html"); ?>
  <body>
    <?php show_flash_message() ?> 
    
    <h1>Post</h1>
    <?php require_once("./templates/navigation_bar.html"); ?>
  
    
    <?php get_data_session("user")["email"]; ?>
  </body>
</html>