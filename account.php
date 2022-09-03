<?php 
  session_start();
  require_once("./useful_functions.php");
  require_once("./connection.php");


  is_logged();

  $user = get_data_session("user");

  if (verify_action_post("update_account")) {
    list(
      "id" => $id,
      "first_name" => $first_name, 
      "last_name" => $last_name, 
      "email" => $email, 
      "password" => $password, 
      "confirm_password" => $confirm_password
    ) = $_POST;

    if (validate_empty_fields([ $first_name, $last_name ])) {

      if ($password !== $confirm_password) {
        set_data_session("flash_message", "Senhas diferentes");
      } else {
        try {

          // se a senha que esta no form (padrao md5) foi alterada, encripta a nova senha
          if (!(strlen($password) === 32)) {
            $password = md5($password);
          }
          
          $now = now_timestamp();

          $sql = "UPDATE account SET 
            first_name = '$first_name', 
            last_name ='$last_name', 
            email = '$email', 
            password = '$password', 
            updated_at = '$now' 
          WHERE id = $id;";

    
          $conn->query($sql);

          $sql = "SELECT * FROM account WHERE id = $id";
          $conn->query($sql);

          $user = $conn->query($sql)->fetch_assoc();
      
          
          set_data_session("user", $user);
          set_data_session("flash_message", "Conta alterada com sucesso");
          
          redirect("post");

        } catch (\Throwable $th) {
          set_data_session("flash_message", "Erro ao editar conta: $th");
        }
      }
    } else {
      set_data_session("flash_message", "Nome ou último nome em branco");
    }
  }

?>
<!DOCTYPE html>
<html lang="pt-br">
  <?php require_once("./templates/head.html"); ?>
<body>
  <?php show_flash_message(); ?>

  <h1>Editar conta</h1>
  <h2><?= $user["first_name"] ?></h2>
  <?php require_once("./templates/navigation_bar.html"); ?>
  
  <p>Ultima alteração em: <?= get_formated_date($user["updated_at"]) ?></p>

  <div>
    <form action="" method="POST">
      <input type="hidden" name="action" value="update_account">
      <input type="hidden" name="id" value="<?=$user["id"]?>">

      <div>
        <label for="first_name">Primeiro nome</label>
        <input 
          type="text" 
          name="first_name" 
          placeholder="primeiro nome" 
          id="first_name" 
          value="<?=$user["first_name"]?>"
        >
      </div>

      <div>
        <label for="last_name">Ultimo nome</label>
        <input 
          type="text" 
          name="last_name" 
          placeholder="ultimo nome" 
          id="last_name" 
          value="<?=$user["last_name"]?>"
        >
      </div>

      <div>
        <label for="email">Email</label>
        <input 
          type="email" 
          name="email" 
          placeholder="email" 
          id="email" 
          value="<?=$user["email"]?>"
        >
      </div>

      <div>
        <label for="password">Senha</label>
        <input 
          type="password" 
          name="password" 
          placeholder="senha" 
          id="password" 
          value="<?=$user["password"]?>"
        >
      </div>

      <div>
        <label for="confirm_password">Confirme a senha</label>
        <input 
          type="password" 
          name="confirm_password" 
          placeholder="confirmação de senha" 
          id="confirm_password" 
          value="<?=$user["password"]?>"
        >
      </div>

      <button>Confirmar alterações</button>
    </form>
  </div>
</body>
</html>