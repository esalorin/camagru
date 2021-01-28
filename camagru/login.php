<?php

require_once("db_functions.php");
include_once("valid_password.php");
session_start();

$incorrect = "";
if (isset($_POST[submit_type]) && $_POST[submit_type] == "login")
{
	if (trim($_POST[username]) == "" || trim($_POST[password]) == "")
		$incorrect = "<p style='font-size:12px;'>Invalid username or password field.</p><br/>";
	if (check_user($_POST[username], hash(whirlpool, $_POST[password])) == 0)
	{
		$_SESSION[login] = $_POST[username];
		$_SESSION[passwd] = hash(whirlpool, $_POST[password]);
		header("Location: index.php");
	}
	else
		$incorrect = "<p style='font-size:12px;'>Login Failed! Please make sure that you enter the correct details and that you have activated your account.</p><br/>";
}
else if (isset($_POST[submit_type]) && $_POST[submit_type] == "register")
{
	if (trim($_POST[email]) == "" || trim($_POST[username]) == "" || trim($_POST[password]) == "" || trim($_POST[confirmpassword]) == "" || trim($_POST[password]) != trim($_POST[confirmpassword]) || valid_password($_POST[password]) === FALSE)
		$incorrect = "<p style='font-size:12px;'>Invalid username or password.</p><br/>";
	else if (!filter_var($_POST[email], FILTER_VALIDATE_EMAIL))
		$incorrect = "<p style='font-size:12px;'>Invalid email format.</p><br/>";
	else
	{
		$ret = register($_POST[email], $_POST[username], hash(whirlpool, $_POST[password]));
		if ($ret == 2)
			$incorrect = "<p style='font-size:12px;'>Username or email is already taken.</p><br/>";
		else if ($ret == 0)
			$incorrect = "<p style='font-size:12px;'>Your account has been made, <br /> please verify it by clicking the activation link that has been send to your email.</p>";
		else
			$incorrect = "<p style='font-size:12px;'>Error with registration. Try again later.</p><br/>";
	}
}
else if (isset($_POST[submit_type]) && $_POST[submit_type] == "Send")
{
	if (trim($_POST[email]) == "")
		$incorrect = "<p style='font-size:12px;'>Invalid email field.</p>";
	else if (send_reset_link($_POST[email]) == 0)
		$incorrect = "<p style='font-size:12px;'>Reset link has been sent to your email.</p>";
	else
		$incorrect = "<p style='font-size:12px;'>No account found for this email address.</p>";	
}

if ($_GET["link"] && $_GET["link"] == "login")
{
	$loginform_html = "$incorrect Username:<br/><input type=\"text\" name=\"username\" value=\"\"/>
	<br/><br/>
	Password:<br/><input type=\"password\" name=\"password\" value=\"\"/>
	<br/><br/><br/>
	<input class=\"button\" type=\"submit\" name=\"submit_type\" value=\"login\"/>";
	$links = "<p style='font-size:12px;'>Register <a href='?link=register'>here</a>.</p>
	<p style='font-size:12px;'><a href='?link=reset'>Forgot password?</a></p>";
}
else if ($_GET["link"] && $_GET["link"] == "register")
{
	$loginform_html = "$incorrect Email address:<br/><input type=\"text\" name=\"email\" value=\"\"/><br/><br/>Username:<br/><input type=\"text\" name=\"username\" value=\"\"/>
	<br/><br/>
	Password:<br/>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"password\" name=\"password\" value=\"\"/>
	<div class='tooltip'><img id='info' src='content/info.png'/><span class='tooltiptext'>Password should be at least 8 characters in length and should include at least one upper case letter, one lower case letter, one number, and one special character.</span></div>
	<br/><br/>
	Confirm password:<br/><input type=\"password\" name=\"confirmpassword\" value=\"\"/>
	<br/><br/><br/>
	<input class=\"button\" type=\"submit\" name=\"submit_type\" value=\"register\"/>";
	$links = "<p style='font-size:12px;'>Already have an account? Login <a href='?link=login'>here</a>.</p>";

}
else if ($_GET["link"] && $_GET["link"] == "reset")
{
	$loginform_html = "$incorrect <p>Password reset link will be send to your email.</p><br/>Email:<br/><input type=\"text\" name=\"email\" value=\"\"/>
	<br/><br/><br/>
	<input class=\"button\" type=\"submit\" name=\"submit_type\" value=\"Send\"/>";
}
	

?>

<!DOCTYPE html>
<html>
<head>
	<title>Login / Register</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
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
			<?php echo $loginform_html?>
		</form>
		<?php echo $links?>
	</div>
	<div id="footer">
		<div id="mode" onclick="mode()">
		<div>
	</div>
</body>
</html>