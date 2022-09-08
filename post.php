<?php
  session_start();
  require_once("./useful_functions.php");
  require_once("./connection.php");

  is_logged();
  $user = get_data_session("user");
  $posts = get_posts($conn);

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
        <li><a href="./my_posts.php">Ver meus posts</a></li>
        <li><a href="./account.php">Editar minha conta</a></li>
        <li><a href="?logout=true">Deslogar</a></li>
      </ul>
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
              </ul>
            </div>
          <?php endif; ?>
          <hr>
        </div>
      <?php endforeach; ?>
    </div>
  </body>
</html>