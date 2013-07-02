<!DOCTYPE html>
<html>
<head>
<title>wcbn sub board: archived requests</title>
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

$query = "SELECT id,sub_name,sub_phone,sub_email,taken,regular_host,show_name,to_char(show_date,'Day DD Month') as show_date_t,to_char(show_start,'HH12:MIam') as show_start_t,to_char(show_end,'HH12:MIam') as show_end_t,comment FROM subs WHERE show_date < NOW()::date AND show_date > NOW()::date - interval '1 month' ORDER BY show_date DESC,show_start DESC;";
$slotsResource = pg_query($conn, $query);
pg_close($conn);
print_r($slotsObject);
?>

<div class="container_12">
    <div class="grid_12">
        <h1>wcbn sub board:<span class="subtitle">archived requests</span></h1>
        <ul><li>&rarr; <a href="request.php">request a sub</a> for your show, or</li>
            <li>&rarr; <a href="index.php">see active requests</a></li>
        </ul>
        <p>this is the past month's worth of sub requests</p>
        <ul class="slots">
            <?php 
            while($request = pg_fetch_object($slotsResource)){
                if($request->taken == 'f'){
                    $request->sub_name = "nobody";
                    $aorspan = "span";
                } else {$aorspan = "a";}
                if($request->comment == ''){
                    $request->comment = 'without giving a reason';
                }else{
                    $request->comment = 'saying "'.$request->comment.'"';
                }
            ?>
            <li id="<?php echo $request->id ?>li">
                <<?php echo $aorspan; ?> class="sub_name">
                <?php echo $request->sub_name; ?><?php echo '</'.$aorspan.'>';?>
                took the  <span class="show_name">
                <?php echo $request->show_name;?></span> slot on <span class="show_date">
                <?php echo $request->show_date_t; ?></span> from <span class="show_start">
                <?php echo $request->show_start_t; ?></span> to <span class="show_end">
                <?php echo $request->show_end_t; ?></span> that <span class="regular_host">
                <?php echo $request->regular_host; ?></span> put up<br /><span class="comment">
                <?php echo $request->comment; ?></span>
                    <span class="personinfo">[<span class="sub_phone">
                            <?php echo $request->sub_phone; ?></span> &bull; 
                        <a class="sub_email" href="mailto:<?php echo $request->sub_email; ?>">
                            <?php echo $request->sub_email; ?></a> ]</span>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>
<?php include "footer.php" ?>
</body>
</html>
