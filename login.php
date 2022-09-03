<?php 
  require_once("./connection.php");
  require_once("./useful_functions.php");
  
  if (verify_action_post("login")) {
    
    list(
      "email" => $email, 
      "password" => $password
      ) = $_POST;
      
      try {
        $password = md5($password);
        
        $sql = "SELECT * FROM account WHERE email = '$email' AND password = '$password';";
        
        $user = $conn->query($sql)->fetch_assoc();
        
        if ($user) {
        
        set_data_session("user", $user);

        header("Location: ./post.php"); die();

      } else {
        set_flash_message("Usuário ou senha inválidos");
      }

    } catch (\Throwable $th) {
      set_flash_message("Erro ao tentar verificar login: $th");
    }
  }
?>

<!DOCTYPE html>
<html lang="pt-br">
  <?php require_once("./templates/head.html") ?>
<body>
  
  <?php show_flash_message() ?>

  <h1>Login Page</h1>
  <?php require_once("./templates/navigation_bar.html") ?>

  <form action="" method="POST">
    <input type="hidden" name="action" value="login">
    <div>
      <label for="email">Email</label>
      <input type="text" name="email" id="email" placeholder="Insira seu email">
    </div>
    <div>
      <label for="password">Senha</label>
      <input type="password" id="password" name="password" placeholder="Insira sua senha">
    </div>
    <button>Entrar</button>
  </form>
</body>
</html>