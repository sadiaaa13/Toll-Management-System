<?php
// includes/mail.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

function sendMail($to, $subject, $bodyHtml) {
    $mail = new PHPMailer(true);
    try {
        // SMTP settings — EDIT THESE
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';          // SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'miaksa3102@gmail.com';    // <-- change
        $mail->Password   = 'jsrazdxxhalktbkn';       // <-- change (App Password if Gmail)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('miaksa3102@gmail.com', 'Toll System'); // <-- change
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $bodyHtml;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Mailer Error: " . $mail->ErrorInfo;
    }
}
?>
