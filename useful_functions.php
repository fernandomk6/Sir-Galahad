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
  unset($_SESSION[$index]);
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