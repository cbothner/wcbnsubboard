<?php 

require "mailer.php";

echo "<p><strong>**".date(DATE_RFC822).": sending TOMORROW notices.**</strong></p>";

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

$query = "
    SELECT 
        regular_host,
        email,
        sub_name,
        sub_email,
        show_name,
        to_char(show_date,'Day DD Month') as show_date_t,
        to_char(show_start,'HH12:MMam') as show_start_t,
        to_char(show_end,'HH12:MMam') as show_end_t,
        taken
    FROM subs
    WHERE show_date >= NOW()::date + interval '1 day'
    AND show_date < NOW()::date + interval '2 days'
    ;";
$result = queryDB($query);
while($data = pg_fetch_object($result)) {

    if($data->taken == 'f') { // UNFULFULLED REQUESTS! PANIC
        $subject = "Your sub request for tomorrow is still unclaimed!";
        $data->regular_host = explode(" ", $data->regular_host);
        $body = "<p>Hello ".$data->regular_host[0].",</p>
            
            <p>Uh oh! Your sub request for <strong>".$data->show_name."</strong> on <strong>".$data->show_date_t.'</strong> from <strong>'.$data->show_start_t.'</strong> to <strong>'.$data->show_end_t.'</strong> is <strong style="color:red;">still unfulfilled!</strong></p>

            <p>You might do well to send an email to the list offering up something tasty to any hero who takes your slot.</p>
            
            <p>This notification was sent automatically. Have a lovely day.</p>';
        $recipient = $data->email;
        sendanemail($subject, $body, $recipient, 1);
        echo "<hr />";
    }

    else { // FULFILLED REQUESTS! DON'T FORGET!
        $subject = "Remember: you're signed up to sub for ".$data->regular_host." tomorrow!";
        $data->sub_name = explode(" ", $data->sub_name);
        $body = "<p>Hello ".$data->sub_name[0].",</p>
            
            <p>This is to remind you that you're signed up to sub for <strong>".$data->regular_host."</strong>'s show <strong>".$data->show_name."</strong> on <strong>".$data->show_date_t.'</strong> from <strong>'.$data->show_start_t.'</strong> to <strong>'.$data->show_end_t.'</strong>. That\'s tomorrow! But you knew that already, right?</p>

            <p>Have a great show tomorrow!</p>
            
            <p>This notification was sent automatically. Have a lovely day.</p>';
        $recipient = $data->sub_email;
        sendanemail($subject, $body, $recipient, 1);
        echo "<hr />";
    }
}

?>
