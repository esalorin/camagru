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
	$LOGTEXT = "logout";
	$LOGLINK = "?status=logged_out";
	$username = $_SESSION["login"];
	$email = get_email($_SESSION["login"]);
	if (isset($_GET["liked"]) && $_GET["liked"] != "")
	{
		like($_SESSION["login"], $_GET["liked"]);
	}
	if (is_dir("images"))
	{
		$photos_html;
		$images = get_images($username);
		$delete = "<img class='delete' alt='delete' name='delete' src='content/delete.png' onclick=\"deleting(event)\">";
		
		foreach ($images as $img)
		{
			$likesrc = "";
			$likesrc = did_i_like_this($_SESSION["login"], $img["image_id"]);
			$likescript = "onclick=\"like(this,'".$img["image_id"]."')\"";
			$like = count_likes($img["image_id"]);
			$photos_html = $photos_html."
			<div class='imgdiv'>
				<img src='".$img["path"]."' id='".$img["image_id"]."' class='img'>
				<div class='imgicons'>
				<img class='like' src='".$likesrc."' ".$likescript.">
				<p class='likecount'>".$like."</p>
				".$delete."
				</div>
			</div>";
		}
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
	<link rel="shortcut icon" href="#" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
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
	<div>
		<h1 id="account" style="margin-bottom:0;"><?php echo $username?>   <a href="editaccount.php"><img id="pen" src="content/pencil.png"></a></h1>
	</div>
	<div id="gallery">
		<hr>
		<p id="photostext">Your photos</p>
			<?php echo $photos_html?>
		<div id="pages">
			<p id="pageno"></p>
			<div id="pagebuttons">
				<button id="prev" class="button"><</button>
				<button id="next" class="button">></button>
			</div>
		</div>
	</div>
	<div id="footer">
	<a href="add.php"><img src="content/plus.png" id="cam"></a>
		<div id="mode" onclick="mode()">
		</div>
	</div>
	<div id="popup_background">
		<div id='popup'><p id="popup_text"></p><br/>
		<button class="button" id="ok" onclick="close_popup(event)">OK</button>
		<button  class="button" id="cancel" onclick="close_popup(event)">cancel</button>
	</div>
	</div>
	<script>
		window.onload = (event) => {
			cookies();
			pagination();
		};
		</script>
		<script src="js/mode.js"></script>
		<script src="js/images.js"></script>
</body>
</html>