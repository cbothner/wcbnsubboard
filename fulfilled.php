<!DOCTYPE html>
<html>
<head>
<title>wcbn sub board: fulfilled requests</title>
<script src="jquery-1.9.0.js" type="text/javascript"></script>
<script src="main.js" type="text/javascript"></script>
<link href="main.css" media="all" rel="stylesheet" type="text/css">
<link href="<?php echo ($_COOKIE['wcbnsubboardcolorscheme']!='' ? $_COOKIE['wcbnsubboardcolorscheme'] : 'dark'); ?>.css" media="all" rel="stylesheet" type="text/css">
</head>
<body>

<?php 
$host = "localhost";
$user = "wcbn";
$pass = "Ceci\ n'est\ pas\ une\ BD"; //Surrealist Database by Cameron
$db = "wcbnsubs";
$conn;

$conn = pg_connect("host=$host dbname=$db user=$user password=$pass");
if(!$conn){die("Database is fail!");}

$query = "SELECT id,sub_name,sub_phone,sub_email,regular_host,show_name,to_char(show_date,'Day DD Month') as show_date_t,to_char(show_start,'HH12:MIam') as show_start_t,to_char(show_end,'HH12:MIam') as show_end_t,comment FROM subs WHERE (show_date > NOW()::date OR (show_date = NOW()::date AND show_start > NOW()::time) ) AND taken = 'True' ORDER BY show_date ASC,show_start ASC;";
$slotsResource = pg_query($conn, $query);
pg_close($conn);
print_r($slotsObject);
?>

<div class="container_12">
    <div class="grid_12">
        <h1>wcbn sub board:<span class="subtitle">fulfilled requests</span></h1>
        <ul><li>&rarr; <a href="request.php">request a sub</a> for your show, or</li>
            <li>&rarr; <a href="index.php">see active requests</a></li>
        </ul>
        <p>these folks are heroes: the following are all the upcoming slots covered by subs</p>
        <ul class="slots">
            <?php 
            while($request = pg_fetch_object($slotsResource)){
                if($request->comment == ''){
                    $request->comment = $request->regular_host." didn't give a reason";
                }else{
                    $request->comment = 'because '.$request->regular_host.' says "'.$request->comment.'"';
                }
            ?>
            <li id="<?php echo $request->id ?>li">
                <a class="sub_name" title="<?php echo $request->sub_phone; ?> &bull; <?php echo $request->sub_email; ?>">
                <?php echo $request->sub_name; ?></a>
                is covering for <span class="show_name">
                <?php echo $request->show_name;?></span> on <span class="show_date">
                <?php echo $request->show_date_t; ?></span> from <span class="show_start">
                <?php echo $request->show_start_t; ?></span> to <span class="show_end">
                <?php echo $request->show_end_t; ?></span><br /><span class="comment">
                <?php echo $request->comment; ?></span>
                    <span class="personinfo">[<span class="sub_phone">
                            <?php echo $request->sub_phone; ?></span> &bull; 
                        <a class="sub_email" href="mailto:<?php echo $request->sub_email; ?>">
                            <?php echo $request->sub_email; ?></a> ]
                </span>
            </li>

            <?php } ?>
        </ul>
        <ul><li>&rarr; <a href="archive.php">archive of past requests</a>, in case you were interested</li></ul>
    </div>
</div>
<?php include "footer.php" ?>
</body>
</html>
