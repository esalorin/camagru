<?php
include_once("valid_password.php");
include_once("db_functions.php");
require_once("config/database.php");

$links = "";
$incorrect = "";
$resetdone = 0;
if (isset($_POST["submit_type"]) && $_POST["submit_type"] == "Reset")
{
	if (trim($_POST["newpassword"]) == "" || trim($_POST["confirmpassword"]) == "" || trim($_POST["newpassword"]) != trim($_POST["confirmpassword"]) || valid_password($_POST["newpassword"]) === FALSE)
		$incorrect = "<p>Invalid input fields. Please type and confirm your new password.</p>";
	else if (reset_passwd($_GET["email"], hash(whirlpool, $_POST["newpassword"])) == 0)
	{
		$resetform_html = "<p>Your password has been updated. You can now login <a href='login.php?link=login'>here</a>.</p>";
		$resetdone = 1;
	}
	else
		$incorrect = "<p>Failed to reset password. Note that you can use reset link only once.</p>";
}
else if ($resetdone == 1)
	$resetform_html = "<p>This reset link has already been used.</p>";
if ($resetdone == 0 && isset($_GET['email']) && !empty($_GET['email']) && isset($_GET['reset']) && !empty($_GET['reset']))
{
	$email = $_GET['email'];
	$reset = $_GET['reset'];
	try {
		$db = new PDO("mysql:host=$DB_DNS;port=8889;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = "SELECT `email` from `users` where `email`= ? AND `reset` = ?;";
		$stmt = $db->prepare($query);
		$stmt->execute([$email, $reset]);
		if ($stmt->fetchAll())
		{
			$resetform_html = "$incorrect New password:<br/>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"password\" name=\"newpassword\" value=\"\"/>
			<div class='tooltip'><img id='info' src='content/info.png'/><span class='tooltiptext'>Password should be at least 8 characters in length and should include at least one upper case letter, one lower case letter, one number, and one special character.</span></div>
			<br/><br/>
			Confirm new password:<br/><input type=\"password\" name=\"confirmpassword\" value=\"\"/>
			<br/><br/><br/>
			<input class=\"button\" type=\"submit\" name=\"submit_type\" value=\"Reset\"/>";
		}
		else
			$resetform_html = "<p>Invalid url, please use the link that has been send to your email. Note that you can use reset link only once.</p>";
	}
	catch (PDOException $e) {
		echo 'Failed to reset passwd: ' . $e->getMessage();
	}
	$db = NULL;
}
else if ($resetdone == 0)
	$resetform_html = "<p>Invalid approach, please use the link that has been send to your email.</p>";


?>
<!DOCTYPE html>
<html>
<head>
	<title>Reset password</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="shortcut icon" href="#" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="js/mode.js"></script>
</head>
<body onload="cookies()">
	<div id="topbar">
		<div class="appnamelog">
			<a href="index.php">
				<img src="content/camagru4.png" id="logo">
			</a>
		</div>
	</div>
	<div id="block">
		<form id="loginform" method="POST">
			<?php echo $resetform_html?>
		</form>
	</div>
	<div id="footer">
		<div id="mode" onclick="mode()">
		<div>
	</div>
</body>
</html>