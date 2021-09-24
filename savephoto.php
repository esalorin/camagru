<?php

require_once("db_functions.php");
require_once("session_verify.php");
session_start();

if (!(verify_session($_SESSION["login"], $_SESSION["passwd"])))
{
	$_SESSION["login"] = "";
	header("Location: index.php");
}

$file = uniqid();
$img = imagecreatefromstring(file_get_contents($_POST['src']));
$img2 = imagecreatetruecolor(400, 300);

$exif = exif_read_data($_POST['src']);
if ($exif && isset($exif['Orientation']))	{
	$orientation = $exif['Orientation'];
	if ($orientation != 1)	{
		$deg = 0;
		switch ($orientation)	{
			case 3:
				$deg = 180;
			break;
			case 6:
				$deg = 270;
				break;
				case 8:
				$deg = 90;
			break;
		}
		if ($deg)	{
			$img = imagerotate($img, $deg, 0);
		}
	}
}

$width = imagesx($img);
$height = imagesy($img);

$ratio_orig = $width/$height;

if (4 / 3 < $ratio_orig) {
	$width = (4 / 3) * $height;
} else if (4 / 3 > $ratio_orig) {
	$height = (3 / 4) * $width;
}

imagecopyresampled($img2, $img, 0, 0, 0, 0, 400, 300, $width, $height);
imagedestroy($img);
if (isset($_POST["filter"]) && $_POST['filter'] != "")
{
	$filter = imagecreatefrompng($_POST['filter']);
	imagealphablending($img2, true);
	imagesavealpha($img2, true);
	$x = (int) filter_var($_POST['left'], FILTER_SANITIZE_NUMBER_INT);
	$y = (int) filter_var($_POST['top'], FILTER_SANITIZE_NUMBER_INT);
	imagecopy($img2, $filter, $x, $y, 0, 0, imagesx($filter), imagesy($filter));
	imagedestroy($filter);
}
$path = "images/".$file.".png";
imagepng($img2, $path);
imagedestroy($img2);
savephoto($_SESSION["login"], $path);

echo $path;

?>