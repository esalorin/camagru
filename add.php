<?php
require_once("session_verify.php");
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

if (isset($_POST["submit_type"]) && $_POST["submit_type"] == "OK")
	header("Location: index.php");
if ($_SESSION["login"] && $_SESSION["login"] != "")
{
	$LOGTEXT = array("profile", "logout");
	$LOGLINK = array("account.php", "?status=logged_out");
	$slash = "&nbsp;&nbsp;";
	$photos_html = $photos_html. "<div id=\"add\">
	<div id=\"webcam\">
		<canvas id=\"canvas\">
		</canvas>
		<div id='photodiv'>
			<img id=\"photo\" alt=\"uploaded photo\">
		</div>
		<video id=\"video\" autoplay=\"autoplay\">Video stream not available.
		</video>
		<div id=\"overlay-container\">
			<div id='overlay-div'>
			<img id=\"overlaid\">
			</div>
		</div>
		<div id=\"buttons\">
			<button id=\"photobutton\" class=\"snap\"><img src=\"content/snap.png\" width=\"25px\" height=\"auto\">
			</button>
			<form id=\"upload\" method='post' enctype='multipart/form-data'>
			<input type='file' accept='image/*' name='fileToUpload' id='fileToUpload' />
			<input type='submit' value='Upload' name='upload' id=\"uploadbutton\" class=\"snap\" />
			</form>
			<button id=\"new\" class=\"snap\">New
			</button>
			<button id=\"save\" class=\"snap\">Save
			</button>
		</div>
		<div id=\"filters\">
			<div id=\"1\" class=\"filter\"><img id=\"dog\" class=\"filterimg\" src=\"filters/doge.png\"></div>
			<div id=\"2\" class=\"filter\"><img id=\"eye\" class=\"filterimg\" src=\"filters/eyes.png\"></div>
			<div id=\"3\" class=\"filter\"><img id=\"glitter\" class=\"filterimg\" src=\"filters/glitter.png\"></div>
			<div id=\"7\" class=\"filter\"><img id=\"butterfly\" class=\"filterimg\" src=\"filters/butterfly.png\"></div>
			<div id=\"4\" class=\"filter\"><img id=\"crown\" class=\"filterimg\" src=\"filters/green_crown.png\"></div>
			<div id=\"5\" class=\"filter\"><img id=\"paint\" class=\"filterimg\" src=\"filters/paint.png\"></div>
			<div id=\"6\" class=\"filter\"><img id=\"rainbow\" class=\"filterimg\" src=\"filters/rainbow.png\"></div>
		</div>
		</div>
		<div id=\"output\">";
	if (is_dir("images"))
	{
		$photos_html;
		$images = get_images($_SESSION["login"]);
		foreach ($images as $img)
		{
			$photos_html = $photos_html."
				<img src='".$img["path"]."' id='".$img["image_id"]."'>";
		}
	}
	$photos_html = $photos_html."</div>
		</div>
		<script src=\"js/webcam.js\"></script>";
}
else
{
	$LOGTEXT = "";
	$LOGLINK = "";
	echo "<div id='block'>To be able to add photos you have to be logged in!<br/><br/>
		<a href=\"index.php\"><button class=\"button\">OK</button></a></div>";
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Create</title>
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
									</a>
								</td>
							</tr>
						</table>
					</div>
		<?php echo $photos_html?>
		<div id="footer">
		<div id="mode" onclick="mode()">
		</div>
	</div>
</body>
</html>