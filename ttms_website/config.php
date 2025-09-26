<?php // config.php
session_start();

define('DB_HOST','127.0.0.1:3325');
define('DB_NAME','mydb');
define('DB_USER','root');   // XAMPP default
define('DB_PASS','');       // XAMPP default empty

function db() {
  static $cn = null;
  if ($cn === null) {
    $cn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    mysqli_set_charset($cn, 'utf8mb4');
  }
  return $cn;
}
function dbp($sql, $types, $params) {  // simple prepared helper
  $stmt = mysqli_prepare(db(), $sql);
  if ($types && $params) mysqli_stmt_bind_param($stmt, $types, ...$params);
  mysqli_stmt_execute($stmt);
  return $stmt;
}
function esc($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function post($k,$d=''){ return trim($_POST[$k] ?? $d); }
function require_login(){
  if (empty($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
}
