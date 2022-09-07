<?php
  session_start();
  require_once("./useful_functions.php");
  require_once("./connection.php");

  is_logged();

  $user = get_data_session("user");
  $posts = get_posts($conn);

?>
<!DOCTYPE html>
<html lang="pt-br">
  <?php require_once("./templates/head.html"); ?>
  <body>
    <?php show_flash_message() ?> 
    
    <h1>Post</h1>
    <h2>Bem-vindo <?= $user["first_name"] . " " . $user["last_name"] ?></h2>
    <?php require_once("./templates/navigation_bar.html"); ?>

    <div>
      <ul>
        <li><a href="./create_post.php">Criar post</a></li>
        <li><a href="">Ver meus posts</a></li>
        <li><a href="./account.php">Editar minha conta</a></li>
        <li><a href="?logout=true">Deslogar</a></li>
      </ul>
      <form action="" method="GET">
        <label for="search">Pesquisar</label>
        <input type="text" name="search" id="search" placeholder="Pesquise por seus posts">
        <button>Pesquisar</button>
      </form>
    </div>

    <div>
      <?php foreach($posts as $post): ?>
        <div>
          <h3><?= $post["title"] ?></h3>
          <div>
            <p><?= $post["body"] ?></p>
          </div>
          <div>
            <span>Autor: <?= $post["autor"] ?></span>
          </div>
          <div>
            <span>Cadastrado em: <?= get_formated_date($post["created_at"]) ?></span>
          </div>
          <div>
            <span>Ultima alteração em: <?= get_formated_date($post["updated_at"]) ?></span>
          </div>
          <div>
            <span>Categorias</span>
            <ul>
              <?php foreach($post["categories"] as $category): ?>
                <li><?= $category["name"] ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <hr>
        </div>
      <?php endforeach; ?>
    </div>
  </body>
</html>