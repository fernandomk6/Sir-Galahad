<?php
  session_start();
  require_once("./useful_functions.php");
  require_once("./connection.php");

  is_logged();

  $user = get_data_session("user");

  $categories = get_categories($conn);

  if (verify_action_post("create_post")) {

    list(
      "account_id" => $account_id,
      "title" => $title,
      "body" => $body
    ) = $_POST;
  
  
    if (validate_empty_fields([$title, $body])) {
      if (isset($_POST["categories"]) && !empty($_POST["categories"])) {
        try {
          $sql = "INSERT INTO post (title, body, account_id) 
          VALUES ('$title', '$body', $account_id);";
          $conn->query($sql);

          $post_id = $conn->insert_id;

          foreach ($_POST["categories"] as $category) {
            $sql = "INSERT INTO post_for_category (post_id, category_id) VALUES($post_id, $category)";
            $conn->query($sql);
          }

          redirect("post");

        } catch (\Throwable $th) {
          set_data_session("flash_message", "Erro ao postar seu conteudo: $th");
        }
      }
      set_data_session("flash_message", "Selecione pelo menos uma categoria");
    } else {
      set_data_session("flash_message", "Titulo ou corpo do post em branco");
    }
    
  }

?>
<!DOCTYPE html>
<html lang="pt-br">
<?php require_once("./templates/head.html"); ?>
<body>
  <?php show_flash_message(); ?>
  <h1>Crie seu Post</h1>
  <?php require_once("./templates/navigation_bar.html"); ?>

  <ul>
    <li><a href="">Ver meus posts</a></li>
    <li><a href="./account.php">Editar minha conta</a></li>
    <li><a href="?logout=true">Deslogar</a></li>
  </ul>

  <div>
    <form action="" method="POST">
      <input type="hidden" name="action" value="create_post">
      <input type="hidden" name="account_id" value="<?= $user['id'] ?>">

      <div>
        <label for="title">Título</label>
        <input type="text" name="title" id="title">
      </div>

      <div>
        <label for="body">Corpo</label>
        <div>
          <textarea name="body" id="body" cols="30" rows="10"></textarea>
        </div>
      </div>

      <div>
        <div>
          <span>Categorias</span>
        </div>
        <?php foreach($categories as $category): ?>
        <div>
          <div>
            <label for="<?= $category["name"]?>">
              <?= $category["name"]?>
            </label>
            <input 
              type="checkbox" 
              name="categories[]" 
              value="<?= $category["id"] ?>"
              id="<?= $category["name"]?>"
            >
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div>
        <button>Postar</button>
      </div>
    </form>
  </div>
</body>
</html>