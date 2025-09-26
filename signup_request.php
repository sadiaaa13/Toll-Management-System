<?php
require 'config.php';
$d = body();
$required = ['name','email','phone','password','vehicle_number'];
foreach($required as $k){ if(empty($d[$k])) return json_err("Missing: $k"); }
$name=$d['name']; $email=$d['email']; $phone=$d['phone'];
$veh=$d['vehicle_number']; $phash=password_hash($d['password'], PASSWORD_BCRYPT);

$stmt = $mysqli->prepare("INSERT INTO pending_users(name,email,phone,password_hash,vehicle_number) VALUES(?,?,?,?,?)");
$stmt->bind_param('sssss',$name,$email,$phone,$phash,$veh);
if(!$stmt->execute()){
  if($mysqli->errno==1062) return json_err("Email already requested/registered");
  return json_err("DB error");
}
json_ok(["message"=>"Signup request submitted. Wait for admin approval email."]);
