<?php

require "mailer.php";

if(isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    switch($action) {
    case 'requestsubmit'      : requestsubmit();break;
    case 'requestdelete'      : requestdelete();break;
    case 'takesubmit'         : takesubmit();break;
    }
}

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

function requestsubmit() {
    $data = array(
        regular_host => $_POST['regular_host'],
        removal_password => $_POST['removal_password'],
        email => $_POST['email'],
        phone => $_POST['phone'],
        show_name => $_POST['show_name'],
        show_date => $_POST['show_date'],
        show_start => $_POST['show_start'],
        show_end => $_POST['show_end'],
        comment => $_POST['comment']
    );
    while(list($key,$value) = each($data)) {
        $value = pg_escape_string($value);
        if(strlen($value) == 0) {
            $data[$key] = 'NULL';
        } else {
            $data[$key] = "'".$value."'";
        }
    }

    $query = "
        INSERT INTO subs
        (   id,
            time_entered,
            regular_host,
            removal_password,
            email,
            phone,
            show_name,
            show_date,
            show_start,
            comment,
            show_end )
        VALUES
        (   nextval('subs_id_seq'),
            NOW(),
            ".$data["regular_host"].",
            ".$data["removal_password"].",
            ".$data["email"].",
            ".$data["phone"].",
            ".$data["show_name"].",
            ".$data["show_date"].",
            ".$data["show_start"].",
            ".$data["comment"].",
            ".$data["show_end"]." );
    SELECT currval('subs_id_seq');";
    $result = queryDB($query);

    if(!$result){ return 1;}
    else{$idObj = pg_fetch_object($result);}

    $requestData = pg_fetch_object(queryDB("SELECT regular_host,show_name,to_char(show_date,'Day DD Month') as show_date_t,to_char(show_start,'HH12:MMam') as show_start_t,to_char(show_end,'HH12:MMam') as show_end_t,comment FROM subs WHERE id = ".$idObj->currval.";"));
    $subject = $requestData->regular_host.' needs a sub on '.$requestData->show_date_t.' for '.$requestData->show_name;
    if($requestData->comment == ''){$requestData->comment = "<p>For a very important (unspecified) reason, ".$requestData->regular_host." needs a sub.</p>";}
    else{$requestData->comment = '<p><i>"'.$requestData->comment.'"</i> &mdash;'.$requestData->regular_host.'.</p>';}
        $body = $requestData->comment."
        
        <p>Be a hero! Sub for <strong>".$requestData->show_name."</strong> on <strong>".$requestData->show_date_t.'</strong> from <strong>'.$requestData->show_start_t.'</strong> to <strong>'.$requestData->show_end_t.'</strong>.</p>
        
        <p>If you want to take this slot, visit <a href="http://remley.wcbn.org/subs/">http://remley.wcbn.org/subs/</a> and take it!</p>
        
        <p>This sub request was sent automatically. Have a lovely day.</p>';
    $recipient = 'rfaa-announcement@umich.edu';

    sendanemail($subject, $body, $recipient);

}

function requestdelete() {
    $id = pg_escape_string($_POST["id"]);
    $removal_password = pg_escape_string($_POST["removal_password"]);

    $passwordquery = "
        SELECT id FROM subs
        WHERE id = ".$id." AND removal_password = '".$removal_password."';
    ";
    $passwordresult = pg_fetch_object(queryDB($passwordquery));
    
    if(!empty($passwordresult)){
        $deletequery = "
            DELETE FROM subs
            WHERE id = ".$id.";
        ";
        $deleteresult = pg_fetch_object(queryDB($deletequery));
        print_r($deleteresult);   
    }
    else{
        echo "passwordfail";
    }
}

function takesubmit() {
    $id = pg_escape_string($_POST['id']);
    $sub_name = pg_escape_string($_POST['sub_name']);
    $sub_phone = pg_escape_string($_POST['sub_phone']);
    $sub_email = pg_escape_string($_POST['sub_email']);

    $query = "
        UPDATE subs
        SET
        taken = 'True',
        taken_timestamp = NOW(),
        sub_name = '".$sub_name."',
        sub_phone = '".$sub_phone."',
        sub_email = '".$sub_email."'
        WHERE
        id = ".$id.";
    ";

    $result = queryDB($query);
    $resultData = pg_fetch_object($result);
    print_r($resultData);

    $query = "
        SELECT regular_host,email,sub_name,sub_email,show_name,to_char(show_date,'Day DD Month') as show_date_t,to_char(show_start,'HH12:MMam') as show_start_t,to_char(show_end,'HH12:MMam') as show_end_t
        FROM subs
        WHERE id = ".$id.";
    ";
    $result = queryDB($query);
    $resultData = pg_fetch_object($result);

    $subject = "Your sub request was taken!";
        $data->regular_host = explode(" ", $data->regular_host);
    $body = "<p>Hello ".$resultData->regular_host[0].",</p>
        
        <p>Good news! Your sub request for <strong>".$resultData->show_name."</strong> on <strong>".$resultData->show_date_t.'</strong> from <strong>'.$resultData->show_start_t.'</strong> to <strong>'.$resultData->show_end_t.'</strong> was fulfilled.</p>

        <p>You were bailed out by <a href="mailto:'.$resultData->sub_email.'">'.$resultData->sub_name.'</a>. Be sure to say thank you!</p>
        
        <p>This notification was sent automatically. Have a lovely day.</p>';
    $recipient = $resultData->email;
    sendanemail($subject,$body,$recipient);
}

?>
