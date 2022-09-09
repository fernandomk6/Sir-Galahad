<?php 
  require_once("./useful_functions.php");
  require_once("./connection.php");

  $categories = get_categories($conn);
?>
<div>
  <form action="" method="GET" id="search_post_bar">
    <input type="hidden" name="action" value="search_post">

    <label for="title">Titulo</label>
    <input type="text" name="title" id="title">



    <label for="body">Conteúdo</label>
    <input type="text" name="body" id="body">



    <label for="autor">Autor</label>
    <input type="text" name="autor" id="autor">

  
    <?php foreach($categories as $category): ?>
      
      <label for="<?= $category["name"]?>">
        <?= $category["name"]?>
      </label>
      <input 
        type="checkbox" 
        name="categories[]" 
        value="<?= $category["id"] ?>"
        id="<?= $category["name"]?>"
      >

    <?php endforeach; ?>

    <button>Pesquisar</button>
  </form>
  <hr>
</div>