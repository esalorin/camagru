<?php

if(isset($_GET['email']) && !empty($_GET['email']) && isset($_GET['hash']) && !empty($_GET['hash']))
{
	$email = $_GET['email'];
	$hash = $_GET['hash'];
	require_once("config/database.php");
	try {
		$db = new PDO("mysql:host=$DB_DNS;port=8889;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = "SELECT `email`, `hash` from `users` where `email`= ? AND `hash` = ? AND `verified`=0;";
		$stmt = $db->prepare($query);
		$stmt->execute([$email, $hash]);
		if ($stmt->fetchAll())
		{
			$query1 = "UPDATE `users` SET `verified`=1 where `email`= ? AND `hash` = ? AND `verified`=0;";
			$stmt1 = $db->prepare($query1);
			$stmt1->execute([$email, $hash]);
			$message = "<p>Your account has been activated, you can now login <a href='login.php?link=login'>here</a>.</p>";
		}
		else
			$message = "<p>Invalid url or account has already been activated.</p>";
	}
	catch (PDOException $e) {
		echo 'Failed to login user: ' . $e->getMessage();
	}
	$db = NULL;
}
else
	$message = "<p>Invalid approach, please use the link that has been send to your email.</p>";

?>
<!DOCTYPE html>
<html>
<head>
	<title>Verify account</title>
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
		<?php echo $message?>
	</div>
	<div id="footer">
		<div id="mode" onclick="mode()">
		<div>
	</div>
</body>
</html>