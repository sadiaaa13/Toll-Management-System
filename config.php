<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: GET, POST');

$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "mydb";

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
  http_response_code(500);
  echo json_encode(["ok"=>false, "error"=>"DB connection failed"]);
  exit;
}

function json_ok($data=[]){ echo json_encode(["ok"=>true] + $data); }
function json_err($msg, $code=400){ http_response_code($code); echo json_encode(["ok"=>false,"error"=>$msg]); }
function body(){ return $_POST + (json_decode(file_get_contents('php://input'), true) ?: []); }
