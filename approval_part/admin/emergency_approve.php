<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/mail.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php'); exit;
}

$id   = intval($_POST['id'] ?? 0);
$rfid = trim($_POST['rfid_number'] ?? '');

if (!$id || !$rfid) {
    echo "Missing data. <a href='index.php'>Back</a>"; exit;
}

// fetch emergency user
$stmt = $conn->prepare("SELECT * FROM emergency_users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();
if (!$user) { echo "Emergency user not found. <a href='index.php'>Back</a>"; exit; }

// check uniqueness
$chk = $conn->prepare("SELECT id FROM verified_users WHERE rfid_number = ? LIMIT 1");
$chk->bind_param("s", $rfid);
$chk->execute();
$chkR = $chk->get_result();
if ($chkR && $chkR->num_rows > 0) {
    echo "RFID already assigned. <a href='index.php'>Back</a>"; exit;
}

// insert into verified_users
$type = 'emergency';
$initial_balance = 0.00;
$password_value = '1'; // force password as 1

$ins = $conn->prepare("INSERT INTO verified_users 
    (name, email, card_type, phone, password, vehicle_number, rfid_number, balance) 
    VALUES (?,?,?,?,?,?,?,?)");
$ins->bind_param(
    "sssssssd", 
    $user['name'], 
    $user['email'], 
    $type, 
    $user['phone'], 
    $password_value, 
    $user['vehicle_number'], 
    $rfid, 
    $initial_balance
);

if ($ins->execute()) {
    // delete emergency request
    $del = $conn->prepare("DELETE FROM emergency_users WHERE id = ?");
    $del->bind_param("i", $id);
    $del->execute();

    // send email with RFID details
    $subject = "Emergency RFID Approved - Smart Toll";
    $body = "<p>Hi " . htmlspecialchars($user['name']) . ",</p>
             <p>Your emergency RFID has been approved.</p>
             <ul>
               <li>RFID: <b>" . htmlspecialchars($rfid) . "</b></li>
               <li>Initial balance: <b>{$initial_balance}</b></li>
               <li>Type: <b>{$type}</b></li>
             </ul>
             <p>Regards,<br>Smart Toll Team</p>";
    $mailResult = sendMail($user['email'], $subject, $body);

    if ($mailResult === true) {
        header('Location: index.php?ok=1'); exit;
    } else {
        echo "Approved but email failed: $mailResult <br><a href='index.php'>Back</a>";
    }
} else {
    echo "DB error: " . $conn->error . " <a href='index.php'>Back</a>";
}
?>
