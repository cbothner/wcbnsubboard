<?php
function sendanemail($subject, $body, $recipient = "YOUR MAILING LIST", $debugging = 0) {
    require_once('class.phpmailer.php');
    $mail = new PHPMailer();
    $mail->IsSMTP(); // enable SMTP
    $mail->SMTPDebug = $debugging; // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true; // authentication enabled
    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465; // or 587
    $mail->IsHTML(true);
    $mail->Username = "YOUR GMAIL ACCOUNT";
    $mail->Password = "YOUR PASSWORD";
    $mail->SetFrom('YOUR EMAIL ADDRESS','YOUR NAME');
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AddAddress($recipient);
    if(!$mail->Send())
    {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
}
?>
