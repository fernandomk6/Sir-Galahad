<?php 

function validate_empty_fields($fields) {
  foreach ($fields as $field) {
    if (!trim($field)) {
      return false;
    }
  }

  return true;
};

function clear_flash_message () {
  $_SESSION["flash_message"] = "";
}

function set_flash_message($message) {
  $_SESSION["flash_message"] = $message;
}

function show_flash_message() {
  if (isset($_SESSION["flash_message"]) && !empty($_SESSION["flash_message"])) {
    $flash_message = $_SESSION["flash_message"];

    echo "<div>
    <h5>Mensagem do sistema</h5>
    <p>$flash_message</p>
    </div>";
  }

  clear_flash_message();
}

function verify_action_post($action) {
  return (isset($_POST["action"]) && !empty($_POST["action"]) && $_POST["action"] === $action);
}

function set_data_session($index, $data) {
  $_SESSION[$index] = $data;
}

function get_data_session($index) {
  if (isset($_SESSION[$index]) && !empty($_SESSION[$index])) {
    return $_SESSION[$index];
  }
}
