<?php

require_once("session_verify.php");
require_once("db_functions.php");
require_once("valid_password.php");
session_start();

if (isset($_GET["status"]) && $_GET["status"] == "logged_out")
{
		$_SESSION["login"] = "";
		$_SESSION["passwd"] = "";
		session_destroy();
		header("Location: index.php");
}
if (!(verify_session($_SESSION["login"], $_SESSION["passwd"])))
		$_SESSION["login"] = "";

if ($_SESSION["login"] && $_SESSION["login"] != "")
{
	$LOGTEXT = array("profile", "logout");
	$LOGLINK = array("account.php", "?status=logged_out");
	$slash = "&nbsp;&nbsp;";
	$username = $_SESSION["login"];
	$email = get_email($_SESSION["login"]);
}
else
{
	$LOGTEXT = "";
	$LOGLINK = "";
	header("Location: index.php");
}

$incorrect = "";
if (isset($_POST["submit_type"]) && $_POST["submit_type"] == "Change username")
{
	if (trim($_POST["username"]) == "")
		$incorrect = "<p style='font-size:12px;'>Invalid username.</p><br/>";
	else if (update_username($_SESSION["login"], $_POST["username"]) == 0)
	{
		$_SESSION["login"] = $_POST["username"];
		$username = $_POST["username"];
		$incorrect = "<p style='font-size:12px;'>Your username has now been updated!</p><br/>";
	}
	else
		$incorrect = "<p style='font-size:12px;'>Failed to modify ".$_SESSION["login"]." username.</p><br/>";
}
else if (isset($_POST["submit_type"]) && $_POST["submit_type"] == "Change email")
{
	if (trim($_POST["email"]) == ""|| trim($_POST["confirmemail"] == "" || trim($_POST["email"]) != trim($_POST["confirmemail"])) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
		$incorrect = "<p style='font-size:12px;'>Invalid email format.</p><br/>";
	else if (update_email($_SESSION["login"], $_POST["email"]) == 0)
	{
		$email = $_POST["email"];
		$incorrect = "<p style='font-size:12px;'>Your email has now been updated!</p><br/>";
	}
	else
		$incorrect = "<p style='font-size:12px;'>Failed to modify email.</p><br/>";
}
else if (isset($_POST["submit_type"]) && $_POST["submit_type"] == "Change password")
{
	if (trim($_POST["newpassword"]) == "" || trim($_POST["confirmpassword"]) == "" || trim($_POST["newpassword"]) != trim($_POST["confirmpassword"]) || valid_password($_POST["newpassword"]) === FALSE)
		$incorrect = "<p style='font-size:12px;'>Invalid password fields.</p><br/>";
	else if (update_passwd($_SESSION["login"], $_SESSION["passwd"], hash(whirlpool, $_POST["newpassword"])) == 0)
		$incorrect = "<p style='font-size:12px;'>Your password has now been updated!</p><br/>";
	else
		$incorrect = "<p>Failed to modify password.</p>";
}
if (isset($_POST["notifications"]))
{
	if ($_POST["notifications"] == "1")
		notifications_on($username);
	else
		notifications_off($username);
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Edit account</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="shortcut icon" href="#" />
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
				</td>
			</tr>
		</table>
	</div>
	<h1 id="account" style="margin-bottom:0;"><?php echo $username?></h1><br/>
	<div id="accountblock">
		<?php echo $incorrect?>
		<form method="POST">
			Username:<br/><input type="text" placeholder=<?php echo $username?> name="username" value=""/><br/>
			<input class="accountbutton" type="submit" name="submit_type" value="Change username"/>
		</form><br/><br/>
		<form method="POST">
			Email address:<br/><input type="text" placeholder="New email" name="email" value=""/>
			<input type="text" name="confirmemail" placeholder="Confirm email" value=""/><br/>
			<input class="accountbutton" type="submit" name="submit_type" value="Change email"/>
		</form><br/><br/>
		<form method="POST">
			Password:<br/><input type="password" placeholder="New password" name="newpassword" value=""/>
			<br/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="password" placeholder="Confirm new password" name="confirmpassword" value=""/>
			<div class='tooltip'><img id='info' src='content/info.png'/><span class='tooltiptext'>Password should be at least 8 characters in length and should include at least one upper case letter, one lower case letter, one number, and one special character.</span></div>
			<input class="accountbutton" type="submit" name="submit_type" value="Change password"/>
		</form><br/><br/>
		Notifications
		<form method="POST">
			<label class="switch">
				<input type="hidden" name="notifications" value="0">
				<input type="checkbox" name="notifications" value="1" onchange="this.form.submit()" <?php echo (notifications($username) == 1) ? "checked" : "" ?>>
				<span class="slider round"></span>
			</label>
		</form>
	</div>
	<div id="footer">
	<a href="#"><img src="content/plus.png" id="cam"></a>
		<div id="mode" onclick="mode()">
		</div>
	</div>
</body>
</html>