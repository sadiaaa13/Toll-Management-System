<?php
require 'config.php';
$d = body();
if(empty($d['rfid_number']) || empty($d['amount'])) return json_err("rfid_number & amount required");
$rfid=$d['rfid_number']; $amt = floatval($d['amount']);
if($amt <= 0) return json_err("Invalid amount");

$mysqli->begin_transaction();
try{
  $ins = $mysqli->prepare("INSERT INTO recharge(rfid_number,amount) VALUES(?,?)");
  $ins->bind_param('sd',$rfid,$amt);
  if(!$ins->execute()) throw new Exception("insert");

  $upd = $mysqli->prepare("UPDATE verified_users SET balance = balance + ? WHERE rfid_number=?");
  $upd->bind_param('ds',$amt,$rfid);
  if(!$upd->execute() || $upd->affected_rows==0) throw new Exception("update");

  $mysqli->commit();
  json_ok(["message"=>"Recharge successful"]);
}catch(Exception $e){
  $mysqli->rollback();
  json_err("Recharge failed");
}
