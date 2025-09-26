<?php
require_once __DIR__ . '/../includes/db.php';
header('Content-Type: application/json');

$card_uid = trim($_REQUEST['card_uid'] ?? '');
if ($card_uid === '') {
    echo json_encode(['success'=>false,'error'=>'card_uid required']); exit;
}

$st = $conn->prepare("SELECT id, name, email, card_type, phone, vehicle_number, balance FROM verified_users WHERE rfid_number = ? LIMIT 1");
$st->bind_param("s", $card_uid);
$st->execute();
$res = $st->get_result();
$user = $res->fetch_assoc();

if ($user) {
    echo json_encode(['success'=>true,'found'=>true,'user'=>$user]);
} else {
    echo json_encode(['success'=>true,'found'=>false]);
}
exit;
?>
