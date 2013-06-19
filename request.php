<!DOCTYPE html>
<html>
<head>
<title>wcbn sub board: make a request</title>
<script src="jquery-1.9.0.js" type="text/javascript"></script>
<script src="main.js" type="text/javascript"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="jquery.maskedinput.min.js" type="text/javascript"></script>
<style type="text/css">@import url('main.css');@import url('http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');</style>
</head>
<body onload="$('#regular_host').focus();">

<div class="container_12">
    <div class="grid_12">
        <h1>wcbn sub board:<span class="subtitle">make a request</span></h1>
        <ul><li>&rarr; <a href="index.php">see active requests</a>, or</li>
            <li>&rarr; <a href="fulfilled.php">see fulfilled requests</a></li>
        </ul>
        <p>can't make your show? request a sub by filling out this form</p>
        <form class="requestform">
            <label for="regular_host">your name</label>
                <input type="text" id="regular_host" /><br />
            <label for="removal_password">password for removal</label>
                <input type="password" id="removal_password" /><br />
            <label for="email">email address</label>
                <input type="email" id="email" /><br />
            <label for="phone">phone number</label>
                <input type="tel" id="phone" /><br />
            <label for="show_name">show name</label>
                <input type="text" id="show_name" /><br />
            <label for="show_date">show date</label>
                <input type="date" id="show_date" /><br />
            <label for="show_start">show start time</label>
                <input type="time" id="show_start" /> <br />
            <label for="show_end">show end time</label>
                <input type="time" id="show_end" /> <br />
            <label for="comment">why?</label>
                <textarea id="comment" height="2"></textarea><br />
            <label>&nbsp;</label>
            <input type="button" value="submit" id="requestsubmit" />
            
        </form>
    </div>
</div>
</body>
</html>
