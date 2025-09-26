<?php
require 'config.php';
$d = body();
if(empty($d['rfid_number']) || empty($d['password'])) return json_err("rfid_number & password required");

$rfid=$d['rfid_number'];

$stmt = $mysqli->prepare("SELECT id,name,email,phone,password,vehicle_number,rfid_number,balance FROM verified_users WHERE rfid_number=?");
$stmt->bind_param('s',$rfid);
$stmt->execute();
$res = $stmt->get_result();
if(!$u = $res->fetch_assoc()) return json_err("User not found / not verified", 401);

if(!password_verify($d['password'], $u['password'])) return json_err("Invalid password", 401);

unset($u['password']);
json_ok(["user"=>$u]);  // client stores rfid locally
