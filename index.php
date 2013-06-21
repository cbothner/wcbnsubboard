<!DOCTYPE html>
<html>
<head>
<title>wcbn sub board: active requests</title>
<script src="jquery-1.9.0.js" type="text/javascript"></script>
<script src="main.js" type="text/javascript"></script>
<script src="jquery.maskedinput.min.js" type="text/javascript"></script>
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

$query = "SELECT id,regular_host,phone,email,show_name,to_char(show_date,'Day DD Month') as show_date_t,to_char(show_start,'HH12:MMam') as show_start_t,to_char(show_end,'HH12:MMam') as show_end_t,comment FROM subs WHERE (show_date > NOW()::date AND taken = 'False') OR (show_date = NOW()::date AND show_start > NOW()::time AND taken = 'False') ORDER BY show_date ASC,show_start ASC;";
$slotsResource = pg_query($conn, $query);
pg_close($conn);
print_r($slotsObject);
?>

<div class="container_12">
  <div class="grid_12">
    <h1>wcbn sub board:<span class="subtitle">active requests</span></h1>
    <ul><li>&rarr; <a href="request.php">request a sub</a> for your show, or</li>
      <li>&rarr; <a href="fulfilled.php">see fulfilled requests</a></li>
    </ul>
    <p>be a hero: take one of the following slots.</p>
    <ul class="slots">
      <?php 
      while($request = pg_fetch_object($slotsResource)){
      if($request->comment == ''){
      $request->comment = 'no reason was given';
      }else{
      $request->comment = '"'.$request->comment.'"';
      }
      ?>
      <li id="<?php echo $request->id ?>li">
      <a class="regular_host" title="<?php echo $request->phone; ?> &bull; <?php echo $request->email; ?>"><?php echo $request->regular_host; ?></a> needs you for <span class="show_name">
        <?php echo $request->show_name;?></span> on <span class="show_date">
        <?php echo $request->show_date_t; ?></span> from <span class="show_start">
        <?php echo $request->show_start_t; ?></span> to <span class="show_end">
        <?php echo $request->show_end_t; ?></span>. <a class="take" id="<?php echo $request->id ?>">take the slot</a> <a class="delete" id="<?php echo $request->id ?>">[X]</a><br /><span class="comment">
        <?php echo $request->comment; ?></span>
      <span class="personinfo">[<span class="phone">
          <?php echo $request->phone; ?></span> &bull; 
        <a class="email" href="mailto:<?php echo $request->email; ?>">
          <?php echo $request->email; ?></a> ]
      </span>
      </li>

      <?php } ?>
    </ul>
  </div>
</div>
<?php require "footer.php" ?>
</body>
</html>
