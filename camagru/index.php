<?php

require_once("session_verify.php");
session_start();

if ($_GET[status] == "logged_out")
{
		$_SESSION[login] = "";
		$_SESSION[passwd] = "";
		session_destroy();
}
if (!(verify_session($_SESSION[login], $_SESSION[passwd])))
		$_SESSION[login] = "";

if ($_SESSION[login] && $_SESSION[login] != "")
{
	$LOGTEXT = array("profile", "logout");
	$LOGLINK = array("account.php", "?status=logged_out");
	$slash = "&nbsp;&nbsp;";
}
else
{
	$LOGTEXT = array("login", "register");
	$LOGLINK = array("login.php?link=login", "login.php?link=register");
	$slash = " / ";
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Camagru</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="js/mode.js"></script>
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
					<a href=<?php echo $LOGLINK[0]?> id="login">
						<?php echo $LOGTEXT[0]?>
					</a><?php echo $slash?>
					<a href=<?php echo $LOGLINK[1]?> id="login">
						<?php echo $LOGTEXT[1]?>
					</a>
				</td>
			</tr>
		</table>
	</div>
	<div id="footer">
		<a href="add.php"><img src="content/plus.png" id="cam"></a>
		<div id="mode" onclick="mode()">
		</div>
	</div>
</body>
</html>