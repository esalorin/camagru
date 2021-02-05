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
	$LOGTEXT = "";
	$LOGLINK = "";
	header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Create</title>
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
					<h1 id="account"></h1>
					<div id="add">
						<div id="webcam">
							<canvas id="canvas">
							</canvas>
							<img id="photo" alt="uploaded photo">
							<video id="video" autoplay="autoplay">Video stream not available.
							</video>
							<div id="overlay-container">
								<img id="overlaid">
							</div>
							
							<div id="buttons">
								<button id="photobutton" class="snap"><img src="content/snap.png" width="25px" height="auto">
								</button>
								<button id="upload" class="snap">Upload
								</button>
								<button id="new" class="snap">New
								</button>
								<button id="save" class="snap">Save
								</button>
							</div>
							<div id="filters">
								<div id="1" class="filter"><img id="dog" class="filterimg" src="filters/doge.png"></div>
								<div id="2" class="filter"><img id="eye" class="filterimg" src="filters/eyes.png"></div>
								<div id="3" class="filter"><img id="glitter" class="filterimg" src="filters/glitter.png"></div>
								<div id="4" class="filter"><img id="crown" class="filterimg" src="filters/green_crown.png"></div>
								<div id="5" class="filter"><img id="noisy" class="filterimg" src="filters/noisy-background.png"></div>
								<div id="6" class="filter"><img id="rainbow" class="filterimg" src="filters/rainbow.png"></div>
							</div>
							</div>
							<div id="output">
							</div>
							</div>
							<script src="js/webcam.js"></script>
		<div id="footer">
		<div id="mode" onclick="mode()">
		</div>
	</div>
</body>
</html>