<?php
function sendanemail($subject, $body, $recipient = "rfaa-mod@umich.edu", $debugging = 0) {
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
    $mail->Subject = preg_replace('/ +/',' ',$subject);
    $mail->Body = $body;
    $plaintext = trim(preg_replace('/ +/',' ',$body));
    $plaintext = preg_replace('/&mdash;/','---',$plaintext);
    $plaintext = strip_tags($plaintext);
    $mail->AltBody = $plaintext;
    $mail->AddAddress($recipient);
    if(!$mail->Send())
    {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
}
?>
