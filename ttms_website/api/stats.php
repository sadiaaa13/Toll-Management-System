<?php
require __DIR__."/../config.php";

$cn = db();

// Total verified users
$r = mysqli_query($cn, "SELECT COUNT(*) AS c FROM verified_users");
$total_users = mysqli_fetch_assoc($r)['c'] ?? 0;

// Total RFID cards issued
$r = mysqli_query($cn, "SELECT COUNT(rfid_number) AS c FROM verified_users");
$total_cards = mysqli_fetch_assoc($r)['c'] ?? 0;

// Passes today (transactions)
$r = mysqli_query($cn, "SELECT COUNT(*) AS c FROM transactions WHERE DATE(transaction_time)=CURDATE()");
$passes_today = mysqli_fetch_assoc($r)['c'] ?? 0;

// Recharges today
$r = mysqli_query($cn, "SELECT COUNT(*) AS c FROM recharge WHERE DATE(recharge_time)=CURDATE()");
$recharges_today = mysqli_fetch_assoc($r)['c'] ?? 0;

header("Content-Type: application/json");
echo json_encode([
  "total_users" => $total_users,
  "total_cards" => $total_cards,
  "passes_today" => $passes_today,
  "recharges_today" => $recharges_today
]);
