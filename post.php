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

    <div>
      <ul>
        <li><a href="">Criar post</a></li>
        <li><a href="">Ver meus posts</a></li>
        <li><a href="">Editar minha conta</a></li>
        <li><a href="?logout=true">Deslogar</a></li>
      </ul>
      <form action="" method="GET">
        <label for="search">Pesquisar</label>
        <input type="text" name="search" id="search" placeholder="Pesquise por seus posts">
        <button>Pesquisar</button>
      </form>
    </div>
    
    <?php print_r(get_data_session("user")); ?>

  </body>
</html>