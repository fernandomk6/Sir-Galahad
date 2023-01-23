<?php 

function validate_empty_fields($fields) {
  foreach ($fields as $field) {
    if (!trim($field)) {
      return false;
    }
  }

  return true;
};

function verify_action_post($action) {
  return (isset($_POST["action"]) && !empty($_POST["action"]) && $_POST["action"] === $action);
}

function verify_action_get($action) {
  return (isset($_GET["action"]) && !empty($_GET["action"]) && $_GET["action"] === $action);
}

function show_flash_message() {
  if (verify_data_session("flash_message")) {
    $message = get_data_session("flash_message");

    echo "
      <div>
        <span>$message</span>
      </div>
    ";
  }  
  
  clear_data_session("flash_message");
}

function verify_data_session($index) {
  return (isset($_SESSION[$index]) && !empty($_SESSION[$index]));
}

function set_data_session($index, $data) {
  $_SESSION[$index] = $data;
}

function get_data_session($index) {
  if (verify_data_session($index)) {
    return $_SESSION[$index];
  }
}

function clear_data_session($index) {
  $_SESSION[$index] = [];
}

function redirect($page, $clear_sessions = false) {
  if ($clear_sessions) {
    session_unset();
    session_destroy();
  }

  header("Location: ./$page.php"); 
  die();
}

function is_logged() {
  if (!verify_data_session("user") || isset($_GET["logout"])) {
    redirect("index", true);
  }
}

function now_timestamp() {
  setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
  date_default_timezone_set('America/Sao_Paulo');
  return date('Y-m-d H:i:s');
}

function get_formated_date($timestamp) {
  $formated_date = date('m/d/Y H:i:s', strtotime($timestamp));

  if ($formated_date === "01/01/1970 01:00:00") {
    $formated_date = "nunca";
  }

  return $formated_date;
}

function get_categories($conn) {
  $sql = "SELECT * FROM category;";
  return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
}

function get_posts($conn) {
  $posts_formated = [];

  $sql = "SELECT * FROM post WHERE deleted_at IS NULL ORDER BY id DESC;";
  $posts = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

  $sql = "SELECT * FROM post_for_category";
  $posts_for_category = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

  $sql = "SELECT * FROM category";
  $categories = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

  $sql = "SELECT * FROM account";
  $accounts = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

  foreach ($posts as $post) {
    $post_id = $post["id"];

    foreach ($posts_for_category as $post_for_category) {
      if ($post_for_category["post_id"] === $post["id"]) {

        foreach ($categories as $category) {
          if ($post_for_category["category_id"] === $category["id"]) {
            list(
              "id" => $id,
              "name" => $name
            ) = $category;

            $post["categories"][] = ["id" => $id, "name" => $name];
          }
        }
      }
    }

    foreach ($accounts as $account) {
      if ($account["id"] === $post["account_id"]) {
        $post["autor"] = $account["first_name"] . " " . $account["last_name"];
      }
    }

    $sql = "SELECT COUNT(*) AS comment_number FROM comment WHERE post_id = $post_id";
    $comment_number = $conn->query($sql)->fetch_assoc();

    $post["comment_number"] = $comment_number["comment_number"];

    
    $sql = "SELECT COUNT(*) AS enjoyed_number FROM enjoy WHERE post_id = $post_id";
    $enjoyed_number = $conn->query($sql)->fetch_assoc();

    $post["enjoyed_number"] = $enjoyed_number["enjoyed_number"];
    
    $posts_formated[] = $post;
  }

  return $posts_formated;
}

function print_data($data) {
  echo("<pre>");
  print_r($data);
  die();
}

function get_comments($post_id, $conn) {
  $sql = "SELECT * FROM comment WHERE post_id = $post_id";
  $comments = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

  $comments_formated = [];
  foreach ($comments as $comment) {
    $account_id = $comment["account_id"];
    $sql = "SELECT * FROM account WHERE id = $account_id";
    $account = $conn->query($sql)->fetch_assoc();

    $comment["autor"] = $account["first_name"] . " " . $account["last_name"];
    $comments_formated[] = $comment;
  }

  return $comments_formated;
}

function get_own_posts($account_id, $conn) {
  $posts_formated = [];

  $sql = "SELECT * FROM post WHERE deleted_at IS NULL AND account_id = $account_id ORDER BY id DESC;";
  $posts = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

  $sql = "SELECT * FROM post_for_category";
  $posts_for_category = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

  $sql = "SELECT * FROM category";
  $categories = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

  $sql = "SELECT * FROM account";
  $accounts = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

  foreach ($posts as $post) {
    $post_id = $post["id"];

    foreach ($posts_for_category as $post_for_category) {
      if ($post_for_category["post_id"] === $post["id"]) {

        foreach ($categories as $category) {
          if ($post_for_category["category_id"] === $category["id"]) {
            list(
              "id" => $id,
              "name" => $name
            ) = $category;

            $post["categories"][] = ["id" => $id, "name" => $name];
          }
        }
      }
    }

    foreach ($accounts as $account) {
      if ($account["id"] === $post["account_id"]) {
        $post["autor"] = $account["first_name"] . " " . $account["last_name"];
      }
    }

    $sql = "SELECT COUNT(*) AS comment_number FROM comment WHERE post_id = $post_id";
    $comment_number = $conn->query($sql)->fetch_assoc();

    $post["comment_number"] = $comment_number["comment_number"];

    
    $sql = "SELECT COUNT(*) AS enjoyed_number FROM enjoy WHERE post_id = $post_id";
    $enjoyed_number = $conn->query($sql)->fetch_assoc();

    $post["enjoyed_number"] = $enjoyed_number["enjoyed_number"];
    
    $posts_formated[] = $post;
  }

  return $posts_formated;
}

function get_post($post_id, $conn) {
  $posts_formated = [];

  $sql = "SELECT * FROM post WHERE deleted_at IS NULL AND id = $post_id ORDER BY id DESC;";
  $posts = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

  $sql = "SELECT * FROM post_for_category";
  $posts_for_category = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

  $sql = "SELECT * FROM category";
  $categories = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

  $sql = "SELECT * FROM account";
  $accounts = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

  foreach ($posts as $post) {
    $post_id = $post["id"];

    foreach ($posts_for_category as $post_for_category) {
      if ($post_for_category["post_id"] === $post["id"]) {

        foreach ($categories as $category) {
          if ($post_for_category["category_id"] === $category["id"]) {
            list(
              "id" => $id,
              "name" => $name
            ) = $category;

            $post["categories"][] = ["id" => $id, "name" => $name];
          }
        }
      }
    }

    foreach ($accounts as $account) {
      if ($account["id"] === $post["account_id"]) {
        $post["autor"] = $account["first_name"] . " " . $account["last_name"];
      }
    }

    $sql = "SELECT COUNT(*) AS comment_number FROM comment WHERE post_id = $post_id";
    $comment_number = $conn->query($sql)->fetch_assoc();

    $post["comment_number"] = $comment_number["comment_number"];

    
    $sql = "SELECT COUNT(*) AS enjoyed_number FROM enjoy WHERE post_id = $post_id";
    $enjoyed_number = $conn->query($sql)->fetch_assoc();

    $post["enjoyed_number"] = $enjoyed_number["enjoyed_number"];
    
    $posts_formated[] = $post;
  }

  return $posts_formated;
}