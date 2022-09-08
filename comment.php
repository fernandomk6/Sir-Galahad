<?php 
session_start();
require_once("./useful_functions.php");
require_once("./connection.php");

is_logged();
$user = get_data_session("user");

if (!isset($_GET["post_id"]) || empty($_GET["post_id"])) {
 redirect("index", true); 
} 

$post_id = $_GET["post_id"];

$comments = get_comments($post_id, $conn);

?>
<!DOCTYPE html>
<html lang="pt-br">
<?php require_once("./templates/head.html"); ?>
<body>
  <h1>Todos os comentários</h1>
  <?php require_once("./templates/navigation_bar.html"); ?>

  <?php if($comments): ?>
    <?php foreach($comments as $comment): ?>
    <div>
      <p><?= $comment["body"] ?></p>
      <div>
        <span>Criado por: <?= $comment["autor"]?></span>
      </div>
      <div>
        <span>Criado em: <?= get_formated_date($comment["created_at"])?></span>
      </div>
      <div>
        <span>Editado em: <?= get_formated_date($comment["updated_at"])?></span>
      </div>
      <hr>
    </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div>
      <p>Nenhum comentário feito</p>
    </div>
  <?php endif; ?>
  <a href="./post.php">Voltar</a>
</body>
</html>