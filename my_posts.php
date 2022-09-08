<?php 
session_start();
require_once("./useful_functions.php");
require_once("./connection.php");

is_logged();
$user = get_data_session("user");
?>
<!DOCTYPE html>
<html lang="pt-br">
<?php require_once("./templates/head.html"); ?>
<body>
  <h1>Meus posts</h1>
  <?php require_once("./templates/navigation_bar.html"); ?>

</body>
</html>