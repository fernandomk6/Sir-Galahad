<?php 
session_start();
require_once("./useful_functions.php");
require_once("./connection.php");

is_logged();
$user = get_data_session("user");

$post_id = $_GET["post_id"];

$post = get_post($post_id, $conn)[0];

$categories = get_categories($conn);

if (verify_action_post("update_post")) {

  list(
    "account_id" => $account_id,
    "title" => $title,
    "body" => $body,
    "post_id" =>$post_id
  ) = $_POST;


  if (validate_empty_fields([$title, $body])) {
    
    if (isset($_POST["categories"]) && !empty($_POST["categories"])) {
      try {
        $now = now_timestamp();
        $sql = "UPDATE post SET title = '$title', body = '$body', updated_at = '$now' WHERE id = $post_id;";
        $conn->query($sql);

        $sql = "DELETE FROM post_for_category WHERE post_id = $post_id";
        $conn->query($sql);

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
  <h1>Editar posts</h1>
  <?php require_once("./templates/navigation_bar.html"); ?>

  <div>
    <form action="" method="POST">
      <input type="hidden" name="action" value="update_post">
      <input type="hidden" name="account_id" value="<?= $user['id'] ?>">
      <input type="hidden" name="post_id" value="<?= $post['id'] ?>">

      <div>
        <label for="title">Título</label>
        <input type="text" name="title" id="title" value="<?= $post["title"] ?>">
      </div>

      <div>
        <label for="body">Corpo</label>
        <div>
          <textarea name="body" id="body" cols="30" rows="10"><?= $post["body"] ?></textarea>
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
              <?php foreach ($post["categories"] as $post_category): ?>
                <?php if($post_category["id"] === $category["id"]): ?>
                  checked
                <?php endif; ?>
              <?php endforeach; ?>
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