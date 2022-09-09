<?php 
require_once("./useful_functions.php");
require_once("./connection.php");

$posts = get_posts($conn);

if (verify_action_get("search_post")) {

  list(
    "title" => $title,
    "body" => $body,
    "autor" => $autor
  ) = $_GET;


  
  $result_search = [];

  foreach ($posts as $post) {
    if (
      (strpos($post["autor"], $autor) !== false) &&
      (strpos($post["title"], $title) !== false) &&
      (strpos($post["body"], $body) !== false)
    ) {


      if (isset($_GET["categories"]) && !empty($_GET["categories"])) {
        $categories = $_GET["categories"];
        
        foreach ($categories as $category) {
          foreach ($post["categories"] as $post_category) {
            if (in_array($category, $post_category)) {
              $result_search[] = $post;
            }
          }
        }
      } else {
        $result_search[] = $post;
      }
    }
  }

  $posts = $result_search;
}


?>
<!DOCTYPE html>
<html lang="pt-br">
  <?php require_once("./templates/head.html") ?>
<body>
  <?php show_flash_message(); ?>

  <h1>Sir Galahad</h1>
  <p>Seja bem-vindo</p>
    <?php require_once("./templates/navigation_bar.html"); ?>
    <?php require_once("./templates/search_post_bar.php"); ?>
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
        <?php if (isset($user) && !empty($user)): ?>
          <div>
            <span>Ações</span>
            <ul>
              <li><a href="">Comentar</a></li>
              <li><a href="">Curtir</a></li>
            </ul>
          </div>
        <?php endif; ?>
        <hr>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>