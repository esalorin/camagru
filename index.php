<?php

require_once("session_verify.php");
require_once("db_functions.php");

session_start();

if (isset($_GET["status"]) && $_GET["status"] == "logged_out")
{
		$_SESSION["login"] = "";
		$_SESSION["passwd"] = "";
		session_destroy();
}
if (!(verify_session($_SESSION["login"], $_SESSION["passwd"])))
		$_SESSION["login"] = "";
if ($_SESSION["login"] && $_SESSION["login"] != "")
{
	$LOGTEXT = array("profile", "logout");
	$LOGLINK = array("account.php", "?status=logged_out");
	$slash = "&nbsp;&nbsp;";
	$logged_in = 1;
}
else
{
	$LOGTEXT = array("login", "register");
	$LOGLINK = array("login.php?link=login", "login.php?link=register");
	$slash = " / ";
	$logged_in = 0;
}
if (is_dir("images"))
{
	$photos_html = "";
	$images = get_all_images();
	foreach ($images as $img)
	{
		$likescript = "";
		$delete = "";
		$likesrc = "";
		$likeimg = "";
		if ($logged_in)
		{
			$likeimg = "<img class='like' src='";
			$likesrc = did_i_like_this($_SESSION["login"], $img["image_id"]);
			$likescript = "onclick=\"like(this,'".$img["image_id"]."')\"";
			$likeimg = $likeimg.$likesrc."' ".$likescript.">";
			if ($img["user"] == $_SESSION["login"])
				$delete = "<img class='delete' alt='delete' name='delete' src='content/delete.png' onclick=\"deleting(event)\">";
			$like = count_likes($img["image_id"]);
		}
		$photos_html = $photos_html."
		<div class='imgdiv'>
			<p id='imguser'>@".$img["user"]."</p>
			<img src='".$img["path"]."' id='".$img["image_id"]."' class='img'>
			<div class='imgicons'>
				".$likeimg."
				<p class='likecount'>".$like."</p>
				".$delete."
			</div>
		</div>";
	}
}
if ($logged_in == 1)
	$add_action = "href='add.php'";
else
	$add_action = "href=# onclick=\"open_popup('add')\"";
	
?>

<!DOCTYPE html>
<html>
<head>
	<title>Camagru</title>
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
	<div id="gallery">
		<?php echo $photos_html ?>
		<div id="pages">
			<p id="pageno"></p>
			<div id="pagebuttons">
				<button id="prev" class="button"><</button>
				<button id="next" class="button">></button>
			</div>
		</div>
	</div>
	<div id="footer">
		<a <?php echo $add_action ?>><img src="content/plus.png" id="cam"></a>
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