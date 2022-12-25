<?php
    require '../vendor/autoload.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->isHTML(true);
    $mail->SMTPDebug = SMTP::DEBUG_OFF;
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;
    $mail->SMTPAuth = true;
    $mail->Username = 'sie@encrym.edu.mx';
    $mail->Password = 'vjjspxqdkyfbefdg';
    $mail->setFrom('sie@encrym.edu.mx', 'SIENCRyM');
    $mail->addReplyTo('sie@encrym.edu.mx', 'SIENCRyM');
?>