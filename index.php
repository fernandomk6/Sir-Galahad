<?php 
require_once('./connection.php');

if (isset($_POST['type']) && !empty($_POST['type']) && $_POST['type'] === 'create_account') {

  // extraindo variaveis
  list(
    'first_name' => $first_name, 
    'last_name' => $last_name, 
    'email' => $email, 
    'password' => $password, 
    'confirm_password' => $confirm_password
  ) = $_POST;

  // validando dados
  if ($password !== $confirm_password) {
    die('Senhas diferentes');
  }


  $sql = "INSERT INTO 
  account (first_name, last_name, email, password) 
  VALUES ('$first_name', '$last_name', '$email', '$password');";

  $conn->query($sql);
  $conn->close();
  $_POST = [];
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sir Galahad</title>
  <link rel="stylesheet" href="./css/index.css">
  <script src="./js/index.js"></script>
</head>
<body>
  <h1>Sir Galahad</h1>
  <div>
    <h2>Login</h2>
    <form action="" method="POST" id="login-form">
      <input type="email" name="email" placeholder="email">
      <input type="password" name="password" placeholder="senha">
      <button>Entrar</button>
    </form>
  </div>
  <div>
    <h2>Criar conta</h2>
    <form action="./index.php" method="POST" id="account-form">
      <input type="hidden" name="type" value="create_account">
      <input type="text" name="first_name" placeholder="primeiro nome">
      <input type="text" name="last_name" placeholder="ultimo nome">
      <input type="email" name="email" placeholder="email">
      <input type="password" name="password" placeholder="senha">
      <input type="password" name="confirm_password" placeholder="confirmação de senha">
      <button>Criar conta</button>
    </form>
  </div>
</body>
</html>