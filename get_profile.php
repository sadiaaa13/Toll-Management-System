<?php
require 'config.php';
$rfid = $_GET['rfid_number'] ?? '';
if(!$rfid) return json_err("rfid_number required");
$q = $mysqli->prepare("SELECT name,email,phone,vehicle_number,rfid_number,balance,created_at FROM verified_users WHERE rfid_number=?");
$q->bind_param('s',$rfid); $q->execute();
$r = $q->get_result()->fetch_assoc();
if(!$r) return json_err("Not found",404);
json_ok(["profile"=>$r]);
