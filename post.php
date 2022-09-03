<?php
  session_start();
  require_once("./useful_functions.php");

  if (!verify_data_session("user") || isset($_GET["logout"])) {
    redirect("index", true);
  }

?>
<!DOCTYPE html>
<html lang="pt-br">
  <?php require_once("./templates/head.html"); ?>
  <body>
    <?php show_flash_message() ?> 
    
    <h1>Post</h1>
    <?php require_once("./templates/navigation_bar.html"); ?>
    
    <?php print_r(get_data_session("user")); ?>

    <a href="?logout=true">Deslogar</a>
  </body>
</html>