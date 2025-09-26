<?php
require 'config.php';

$d = body();

if(empty($d['rfid_number']) || empty($d['message'])) {
    json_err("rfid_number and message required");
    exit;
}

$ins = $mysqli->prepare("INSERT INTO lost_car(rfid_number,message,request_time) VALUES(?,?,NOW())");
if(!$ins){
    json_err("Prepare failed: ".$mysqli->error);
    exit;
}
$ins->bind_param('ss',$d['rfid_number'],$d['message']);

if(!$ins->execute()){
    json_err("Insert failed: ".$ins->error);
    exit;
}

json_ok(["message"=>"Report submitted to admin"]);
