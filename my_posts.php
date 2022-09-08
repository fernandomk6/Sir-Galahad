<?php 
session_start();
require_once("./useful_functions.php");
require_once("./connection.php");

is_logged();
$user = get_data_session("user");

if (verify_action_post("create_comment")) {
  list(
    "account_id" => $account_id,
    "post_id" => $post_id,
    "body" => $body
  ) = $_POST;

  $sql = "INSERT INTO comment (body, account_id, post_id)
  VALUES('$body', $account_id, $post_id)";

  try {
    $conn->query($sql);
    die("Comentário realizado com sucesso");
  } catch (\Throwable $th) {
    set_data_session("flash_message", "Erro ao adicionar commentario: $th");
  }
}

if ((isset($_GET["post_id"]) && !empty($_GET["post_id"])) && 
    (isset($_GET["enjoyed"]) && !empty($_GET["enjoyed"]) && $_GET["enjoyed"])) {

  $post_id = $_GET["post_id"];
  $user_id = $user["id"];

  $sql = "SELECT * FROM enjoy";
  $enjoys = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

  foreach ($enjoys as $enjoy) {
    if ($enjoy["account_id"] === $user["id"] && $post_id === $enjoy["post_id"]) {
        die("você já curtiu esse post");
      }
  }


  $sql = "INSERT INTO enjoy (account_id, post_id, enjoyed)
  VALUES($user_id, $post_id, 1);";
  $conn->query($sql);

  die("Você curtiu o post");
} 

if (isset($_GET["delete_post"]) && !empty($_GET["delete_post"])) {

  $post_id = $_GET["delete_post"];
  $now = now_timestamp();

  $sql = "UPDATE post SET deleted_at = '$now' WHERE id = $post_id;";
  $conn->query($sql);

  die("Post excluído");
} 

$own_posts = get_own_posts($user["id"], $conn);
?>
<!DOCTYPE html>
<html lang="pt-br">
<?php require_once("./templates/head.html"); ?>
<body>
  <h1>Meus posts</h1>
  <?php require_once("./templates/navigation_bar.html"); ?>

  <div>
    <?php if($own_posts): ?>
      <?php foreach($own_posts as $post): ?>
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
          <span>Número de curtidas: <?= $post["enjoyed_number"] ?></span>
        </div>
        <div>
          <span>Número de comentários: <?= $post["comment_number"] ?></span>
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
              <li>
                <form action="" method="POST">
                  <input type="hidden" name="action" value="create_comment">
                  <input type="hidden" name="account_id" value="<?= $user["id"]?>">
                  <input type="hidden" name="post_id" value="<?= $post["id"] ?>">
                  <label for="body">Comenário</label>
                  <div>
                    <textarea 
                      name="body" 
                      id="body" 
                      placeholder="Qual a sua opnião sobre isso?" 
                      cols="30" 
                      rows="3"
                    ></textarea>
                  </div>
                  <button>Comentar</button>
                </form>
              </li>
              <li><a href="./comment.php?post_id=<?= $post["id"] ?>">Ver todos os comentários</a></li>
              <li><a href="?enjoyed=true&post_id=<?= $post["id"] ?>">Curtir</a></li>
              <li><a href="?delete_post=<?= $post["id"] ?>">Excluir</a></li>
              <li><a href="./edit_post.php?post_id=<?= $post["id"] ?>">Editar</a></li>
            </ul>
          </div>
        <?php endif; ?>
        <hr>
      </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div>
        <span>Você não postou nada ainda</span>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>