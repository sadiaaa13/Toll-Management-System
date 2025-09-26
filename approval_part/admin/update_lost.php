<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/mail.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $rfid = $_POST['rfid_number'];
    $status = $_POST['status'];

    // get user
    $st = $conn->prepare("SELECT name,email,card_type FROM verified_users WHERE rfid_number = ? LIMIT 1");
    $st->bind_param("s", $rfid);
    $st->execute();
    $u = $st->get_result()->fetch_assoc();

    if ($u) {
        if ($status === 'lost' && $u['card_type'] !== 'lost') {
            // update to lost
            $upd = $conn->prepare("UPDATE verified_users SET card_type = 'lost' WHERE rfid_number = ?");
            $upd->bind_param("s", $rfid);
            $upd->execute();

            // send email
            $subject = "Your RFID has been marked LOST";
            $body = "<p>Dear " . htmlspecialchars($u['name']) . ",</p>
                     <p>Your RFID card <b>$rfid</b> has been reported as <b>LOST</b>. 
                     It is now blocked and cannot be used at toll gates.</p>";
            sendMail($u['email'], $subject, $body);
        } elseif ($status === 'found' && $u['card_type'] === 'lost') {
            // update to valid
            $upd = $conn->prepare("UPDATE verified_users SET card_type = 'valid' WHERE rfid_number = ?");
            $upd->bind_param("s", $rfid);
            $upd->execute();

            // send email
            $subject = "Your RFID has been reactivated";
            $body = "<p>Dear " . htmlspecialchars($u['name']) . ",</p>
                     <p>Your RFID card <b>$rfid</b> has been reported as <b>FOUND</b>. 
                     It is now reactivated and can be used normally again.</p>";
            sendMail($u['email'], $subject, $body);
        }

        // mark report processed (don’t delete history)
        $done = $conn->prepare("UPDATE lost_car SET message = CONCAT(message, ' (processed)') WHERE id = ?");
        $done->bind_param("i", $id);
        $done->execute();
    }
}

header("Location: lostcar.php");
exit;
