<?php

require_once("session_verify.php");
require_once("db_functions.php");
require_once("valid_password.php");
session_start();

if ($_GET[status] == "logged_out")
{
		$_SESSION[login] = "";
		$_SESSION[passwd] = "";
		session_destroy();
		header("Location: index.php");
}
if (!(verify_session($_SESSION[login], $_SESSION[passwd])))
		$_SESSION[login] = "";

if ($_SESSION[login] && $_SESSION[login] != "")
{
	$LOGTEXT = "logout";
	$LOGLINK = "?status=logged_out";
	$username = $_SESSION[login];
	$email = get_email($_SESSION[login]);
	if (is_dir("images"))
	{
		$i = 0;
		$photos_html;
		$photos = get_images($_SESSION[login]);
	}
}
else
{
	$LOGTEXT = "";
	$LOGLINK = "";
	header("Location: index.php");
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Account</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="js/mode.js" type="text/javascript"></script>
</head>
<body onload="cookies()">
	<div id="topbar">
		<table class="topbartable">
			<tr>
				<td class="appname">
					<a href="index.php">
					<img src="content/camagru4.png" id="logo">
					</a>
				</td>
				<td class="logintd">
					<a href=<?php echo $LOGLINK?> id="login">
						<?php echo $LOGTEXT?>
					</a>
				</td>
			</tr>
		</table>
	</div>
	<br/><br/><br/><br/>
	<div>
		<h1 id="account" style="margin-bottom:0;"><?php echo $username?>   <a href="editaccount.php"><img id="pen" src="content/pencil.png"></a></h1>
	</div>
	<div id="photos">
		<hr>
		<p id="photostext">Your photos</p>
		<table>
			<?php echo $photos_html?>
		</table>
	</div>
	<div id="footer">
	<a href="add.php"><img src="content/plus.png" id="cam"></a>
		<div id="mode" onclick="mode()">
		</div>
	</div>
</body>
</html>