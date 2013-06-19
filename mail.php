<?php 
function queryDB($queryString) {
    $host = "localhost";
    $user = "wcbn";
    $pass = "Ceci\ n'est\ pas\ une\ BD"; //Surrealist Database by Cameron
    $db = "wcbnsubs";
    $conn;

    $conn = pg_connect("host=$host dbname=$db user=$user password=$pass");

    if(!$conn){die("Database is fail!");}

    $resultResource = pg_query($conn, $queryString);

    pg_close($conn);
    return $resultResource;
}
        $requestData = pg_fetch_object(queryDB("SELECT regular_host,show_name,to_char(show_date,'Day DD Month') as show_date_t,to_char(show_start,'HH12:MMam') as show_start_t,to_char(show_end,'HH12:MMam') as show_end_t,comment FROM subs WHERE id = 3;"));

        require_once('class.phpmailer.php');

        $mail = new PHPMailer();
        $mail->IsSMTP(); // enable SMTP
        $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true; // authentication enabled
        $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 465; // or 587
        $mail->IsHTML(true);
        $mail->Username = "wcbnsubboard@gmail.com";
        $mail->Password = "jlambers";
        $mail->SetFrom('wcbnsubboard@gmail.com','WCBN Sub Board');
        $mail->Subject = $requestData->regular_host.' needs a sub on '.$requestData->show_date_t.' for '.$requestData->show_name;
        if($requestData->comment == ''){$requestData->comment = "<p>For a very important (unspecified) reason, ".$requestData->regular_host." needs a sub.</p>";}
        else{ $requestData->comment = '<p><i>"'.$requestData->comment.'"</i> &mdash;'.$requestData->regular_host.'</p>';}
            $mail->Body = $requestData->comment."
            
            <p>Be a hero! Sub for <strong>".$requestData->show_name."</strong> on <strong>".$requestData->show_date_t.'</strong> from <strong>'.$requestData->show_start_t.'</strong> to <strong>'.$requestData->show_end_t.'</strong>.</p>
            
            <p>If you want to take this slot, visit <a href="http://remley.wcbn.org/subs/">http://remley.wcbn.org/subs/</a> and take it!</p>
            
            <p>This sub request was sent automatically. Have a lovely day.</p>';
        $mail->AddAddress('utopian.thirteen@gmail.com');
        if(!$mail->Send())
        {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
        else
        {
            echo "Message has been sent";
        }

?>
