<?php
// rfid/ingest.php
// Corrected for the transactions schema: (rfid, c_type, amount, v_number, transaction_time auto)
// This script returns plain text like: STATUS:valid;BALANCE:70.00

// show PHP errors for debugging (remove or disable in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// throw mysqli exceptions on error so try/catch works
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

require_once __DIR__ . '/../includes/db.php';
header('Content-Type: text/plain');

$card = trim($_POST['card_uid'] ?? '');

if ($card === '') {
    echo "STATUS:error;MSG:card_uid required";
    exit;
}

try {
    // fetch user
    $st = $conn->prepare("SELECT id, name, vehicle_number, card_type, balance FROM verified_users WHERE rfid_number = ? LIMIT 1");
    $st->bind_param("s", $card);
    $st->execute();
    $res = $st->get_result();
    $user = $res->fetch_assoc();

    if (!$user) {
        // unknown card - log and return
        $ins = $conn->prepare("INSERT INTO transactions (rfid, c_type, amount, v_number) VALUES (?, ?, ?, ?)");
        $unknown = 'Not Registered';
        $amt = '0.00';
        $vnum = '-';
        $ins->bind_param("ssds", $card, $unknown, $amt, $vnum);
        $ins->execute();

        echo "STATUS:unknown;BALANCE:-";
        exit;
    }

    $card_type = strtolower($user['card_type']);
    $balance = floatval($user['balance']);
    $charge = 30.00;

    if ($card_type === 'emergency') {
        // log emergency (no charge)
        $ins = $conn->prepare("INSERT INTO transactions (rfid, c_type, amount, v_number) VALUES (?, ?, ?, ?)");
        $amt = 0.00;
        $ins->bind_param("ssds", $card, $card_type, $amt, $user['vehicle_number']);
        $ins->execute();

        echo "STATUS:emergency;BALANCE:" . number_format($balance, 2, '.', '');
        exit;
    }

    if ($card_type === 'lost') {
        // log lost (no charge)
        $ins = $conn->prepare("INSERT INTO transactions (rfid, c_type, amount, v_number) VALUES (?, ?, ?, ?)");
        $amt = 0.00;
        $ins->bind_param("ssds", $card, $card_type, $amt, $user['vehicle_number']);
        $ins->execute();

        // optionally notify owner here (see note below)
        echo "STATUS:lost;BALANCE:" . number_format($balance, 2, '.', '');
        exit;
    }

    // Normal valid user
    if ($balance >= $charge) {
        // deduct and insert atomically
        $conn->begin_transaction();
        try {
            $newbalance = round($balance - $charge, 2);

            $upd = $conn->prepare("UPDATE verified_users SET balance = ? WHERE id = ?");
            $upd->bind_param("di", $newbalance, $user['id']);
            $upd->execute();

            $ins = $conn->prepare("INSERT INTO transactions (rfid, c_type, amount, v_number) VALUES (?, ?, ?, ?)");
            $txn = 'valid';
            $ins->bind_param("ssds", $card, $txn, $newbalance, $user['vehicle_number']);
            $ins->execute();

            $conn->commit();

            echo "STATUS:valid;BALANCE:" . number_format($newbalance, 2, '.', '');
            exit;
        } catch (mysqli_sql_exception $e) {
            $conn->rollback();
            error_log("Ingest DB exception: " . $e->getMessage());
            echo "STATUS:error;MSG:db_failure";
            exit;
        }
    } else {
        // insufficient funds -> log invalid
        $ins = $conn->prepare("INSERT INTO transactions (rfid, c_type, amount, v_number) VALUES (?, ?, ?, ?)");
        $txn = 'invalid';
        $amt = 0.00;
        $ins->bind_param("ssds", $card, $txn, $user['balance'], $user['vehicle_number']);
        $ins->execute();

        echo "STATUS:invalid;BALANCE:" . number_format($balance, 2, '.', '');
        exit;
    }

} catch (mysqli_sql_exception $ex) {
    // fatal DB error
    error_log("Ingest fatal DB error: " . $ex->getMessage());
    echo "STATUS:error;MSG:db_failure";
    exit;
}
