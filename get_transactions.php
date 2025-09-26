<?php
require 'config.php';
$rfid = $_GET['rfid_number'] ?? '';
if(!$rfid) return json_err("rfid_number required");
$q = $mysqli->prepare("SELECT id,c_type,amount,transaction_time,v_number FROM transactions WHERE rfid=? ORDER BY id DESC LIMIT 50");
$q->bind_param('s',$rfid); $q->execute();
$res = $q->get_result(); $rows=[];
while($row=$res->fetch_assoc()) $rows[]=$row;
json_ok(["transactions"=>$rows]);
