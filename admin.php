<!DOCTYPE html>
<html>
<head>
<title>wcbn sub board: administration</title>
<script src="jquery-1.9.0.js" type="text/javascript"></script>
<script src="main.js" type="text/javascript"></script>
<script src="jquery.maskedinput.min.js" type="text/javascript"></script>
<link href="main.css" media="all" rel="stylesheet" type="text/css">
<link href="<?php echo ($_COOKIE['wcbnsubboardcolorscheme']!='' ? $_COOKIE['wcbnsubboardcolorscheme'] : 'dark'); ?>.css" media="all" rel="stylesheet" type="text/css">
</head>
<body>


<div class="container_12">
  <div class="grid_12">
    <h1>wcbn sub board:<span class="subtitle">administration</span></h1>
    <ul><li>&rarr; <a href="request.php">new</a></li>
      <li>&rarr; <a href="index.php">active</a></li>
      <li>&rarr; <a href="fulfilled.php">fulfilled</a></li>
      <li>&rarr; <a href="archive.php">archive</a></li>
    </ul>

    <h5 class="show_name">[delete]</h5>
    <p>what request would you like to delete? find its id by hovering your mouse over it on one of the list pages.</p>
    <form action="admin.php" method="post" class="deleteform">
      <input type="hidden" name="action" value="delete-confirm">
      <label for="id">delete request with id</label> <input type="text" name="id" size="3" /> <input type="submit" value="delete it" />
    </form>

<!-- DELETE CONFIRM SEGMENT -->
<?php if ($_POST['action'] == "delete-confirm") { ?>
    <?php 
    $host = "localhost";
    $user = "wcbn";
    $pass = "Ceci\ n'est\ pas\ une\ BD"; //Surrealist Database by Cameron
    $db = "wcbnsubs";
    $conn;

    $conn = pg_connect("host=$host dbname=$db user=$user password=$pass");
    if(!$conn){die("Database is fail!");}

    $query = "SELECT id,taken,sub_name,sub_phone,sub_email,regular_host,show_name,to_char(show_date,'Day DD Month') as show_date_t,to_char(show_start,'HH12:MIam') as show_start_t,to_char(show_end,'HH12:MIam') as show_end_t,comment,(show_date = NOW()::date AND show_start < NOW()::time) as elapsed FROM subs WHERE id = ".$_POST['id'].";";
    $slotsResource = pg_query($conn, $query);
    pg_close($conn);
    print_r($slotsObject);
    ?>
        <ul class="slots">
          <?php 
          $request = pg_fetch_object($slotsResource);
          if($request->comment == ''){
          $request->comment = 'no reason was given';
          }else{
          $request->comment = '"'.$request->comment.'"';
          }
          if($request->taken == 't'){
              $activehoststring = '<span class="sub_name">'.$request->sub_name.'</span> subbing on';
          }
          else{
            $activehoststring = '<span class="regular_host">'.$request->regular_host.'</span> requesting a sub for';
          }
          ?>
    <?php if (!empty($request->id)){ ?>
    <p style="margin-top:1em" class="show_name";>are you sure you want to delete this request?</p>
          <li id="<?php echo $request->id ?>li">
          <?php echo $activehoststring; ?> <span class="show_name">
            <?php echo $request->show_name;?></span> on <span class="show_date">
            <?php echo $request->show_date_t; ?></span> from <span class="show_start">
            <?php echo $request->show_start_t; ?></span> to <span class="show_end">
            <?php echo $request->show_end_t; ?></span>?<br /><span class="comment">
            <?php echo $request->comment; ?></span>
          </li>
        </ul>
    <form action="admin.php" method="post" >
      <input type="hidden" name="action" value="delete">
      <input type="hidden" name="id" value="<?php echo $request->id; ?>">
      <input type="submit" value="really delete it!"/>
    </form>

    <?php }else{ ?>
        <li></li>
        <li>there is no request with id <?php echo $_POST["id"] ?> in the database</li>
    <?php } ?>

<?php } ?>

<!-- DELETE SEGMENT -->
<?php if ($_POST['action'] == "delete") { ?>
    <?php 
    $host = "localhost";
    $user = "wcbn";
    $pass = "Ceci\ n'est\ pas\ une\ BD"; //Surrealist Database by Cameron
    $db = "wcbnsubs";
    $conn;

    $conn = pg_connect("host=$host dbname=$db user=$user password=$pass");
    if(!$conn){die("Database is fail!");}

    $query = "DELETE FROM subs WHERE id = ".$_POST['id'].";";
    //$slotsResource = pg_query($conn, $query);
    pg_close($conn);
    print_r($query);
    print_r($slotsResource);
    ?>

<p class="show_name">not currently deleting things until password works</p>


<?php } ?>
  </div>
</div>
<?php require "footer.php" ?>
</body>
</html>
