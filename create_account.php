<?php 
  session_start();

  require_once("./connection.php");
  require_once("./useful_functions.php");

  if (verify_action_post("create_account")) {
    list(
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
          $password = md5($password);
  
          $sql = "INSERT INTO 
          account (first_name, last_name, email, password) 
          VALUES ('$first_name', '$last_name', '$email', '$password');";
    
          $conn->query($sql);
    
          set_data_session("flash_message", "Conta criada com sucesso");
        } catch (\Throwable $th) {
          set_data_session("flash_message", "Erro ao cadastrar conta: $th");
        }
      }
    } else {
      set_data_session("flash_message", "Nome ou último nome em branco");
    }
  }
?>
<!DOCTYPE html>
<html lang="pt-br">
  <?php require_once("./templates/head.html") ?>
<body>
  <?php show_flash_message() ?>

  <h1>Sir Galahad</h1>
  <?php require_once("./templates/navigation_bar.html") ?>

  <div>
    <h2>Criar conta</h2>
    <form action="" method="POST">
      <input type="hidden" name="action" value="create_account">

      <div>
        <label for="first_name">Primeiro nome</label>
        <input type="text" name="first_name" placeholder="primeiro nome" id="first_name">
      </div>

      <div>
        <label for="last_name">Ultimo nome</label>
        <input type="text" name="last_name" placeholder="ultimo nome" id="last_name">
      </div>

      <div>
        <label for="email">Email</label>
        <input type="email" name="email" placeholder="email" id="email">
      </div>

      <div>
        <label for="password">Senha</label>
        <input type="password" name="password" placeholder="senha" id="password">
      </div>

      <div>
        <label for="confirm_password">Confirme a senha</label>
        <input type="password" name="confirm_password" placeholder="confirmação de senha" id="confirm_password">
      </div>

      <button>Criar conta</button>
    </form>
  </div>
</body>
</html>