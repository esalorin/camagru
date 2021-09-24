<?php

function register($email, $user, $passwd)
{
	require_once("config/database.php");
	try {
		$db = new PDO("mysql:host=$DB_DNS;port=8889;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = "SELECT `username` from `users` where `username` = ? or `email` = ?;";
		$stmt = $db->prepare($query);
		$stmt->execute([$user, $email]);
		if ($stmt->fetchAll())
			return (2);
		else
		{
			$sql = "INSERT into `users` (`username`, `email`, `password`, `hash`, `reset`)
			values (?, ?, ?, ?, ?);";
			$stmt1 = $db->prepare($sql);
			$hash = md5(rand(0,1000));
			$reset = md5(rand(1000,5000));
			$stmt1->execute([$user, $email, $passwd, $hash, $reset]);

			$url = "http://127.0.0.1:8080/camagru/verify.php?email=".$email."&hash=".$hash;
			$subject = "Account verification";
			$message = "
			Hi ".$user."!

			Thanks for signing up!
			Your account has been created, you can activate your account by clicking the link below.
			".$url;
			$headers = "From:camagru <noreply@camagru.com>"."\r\n";
			mail($email, $subject, $message, $headers);
			return (0);
		}
	}
	catch (PDOException $e) {
		echo 'Failed to register user: ' . $e->getMessage();
		return (1);
	}
	$db = NULL;
}

function check_user($login, $passwd)
{
	require_once("config/database.php");
	try {
		$db = new PDO("mysql:host=$DB_DNS;port=8889;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = "SELECT `username` from `users` where `username`= ? AND `password` = ? AND `verified`=1;";
		$stmt = $db->prepare($query);
		$stmt->execute([$login, $passwd]);
		if ($stmt->fetchAll())
			return (0);
		else
			return (2);
	}
	catch (PDOException $e) {
		echo 'Failed to login user: ' . $e->getMessage();
		return (1);
	}
	$db = NULL;
}

function send_reset_link($email)
{
	require_once("config/database.php");
	try {
		$db = new PDO("mysql:host=$DB_DNS;port=8889;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = "SELECT `username` from `users` where `email`= ?;";
		$stmt = $db->prepare($query);
		$stmt->execute([$email]);
		if ($res = $stmt->fetchAll())
		{
			$reset = md5(rand(1000,5000));
			$query = "UPDATE `users` SET `reset`= ? WHERE `email` = ?;";
			$stmt1 = $db->prepare($query);
			$stmt1->execute([$reset, $email]);

			$url = "http://127.0.0.1:8080/camagru/reset.php?email=".$email."&reset=".$reset;
			$subject = "Reset link";
			$message = "
			Hi ".$res[0][username]."!

			Reset you password by clicking the link below.
			".$url;
			$headers = "From:camagru <noreply@camagru.com>"."\r\n";
			mail($email, $subject, $message, $headers);
			return (0);
		}
		else
			return (2);
	}
	catch (PDOException $e) {
		echo 'Error: ' . $e->getMessage();
		return (1);
	}
	$db = NULL;
}

function reset_passwd($email, $passwd)
{
	require("config/database.php");
	try {
		$db = new PDO("mysql:host=$DB_DNS;port=8889;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = "SELECT `username` from `users` where `email`= ?;";
		$stmt = $db->prepare($query);
		$stmt->execute([$email]);
		if ($stmt->fetchAll())
		{
			$reset = md5(rand(1000,5000));
			$query1 =  "UPDATE `users` SET `password`= ?, `reset` = ? WHERE `email` = ?;";
			$stmt1 = $db->prepare($query1);
			$stmt1->execute([$passwd, $reset, $email]);
			return (0);
		}
		else
			return (2);
	}
	catch (PDOException $e) {
		echo 'Failed to reset password: ' . $e->getMessage();
		return (1);
	}
	$db = NULL;
}

function update_passwd($username, $passwd, $newpasswd)
{
	require("config/database.php");
	try {
		$db = new PDO("mysql:host=$DB_DNS;port=8889;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = "SELECT `username` from `users` WHERE `username` = ? AND `password` = ?;";
		$stmt = $db->prepare($query);
		$stmt->execute([$username, $passwd]);
		if ($stmt->fetchAll())
		{
			$query1 =  "UPDATE `users` SET `password`= ? WHERE `username` = ?;";
			$stmt1 = $db->prepare($query1);
			$stmt1->execute([$newpasswd, $username]);
			return (0);
		}
		else
			return (2);
	}
	catch (PDOException $e) {
		echo 'Failed to modify username: ' . $e->getMessage();
		return (1);
	}
	$db = NULL;
}

function update_username($username, $newusername)
{
	require("config/database.php");
	try {
		$db = new PDO("mysql:host=$DB_DNS;port=8889;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = "SELECT `username` from `users` WHERE `username` = ?;";
		$stmt = $db->prepare($query);
		$stmt->execute([$username]);
		if ($stmt->fetchAll())
		{
			$query1 =  "UPDATE `users` SET `username`= ? WHERE `username` = ?;";
			$stmt1 = $db->prepare($query1);
			$stmt1->execute([$newusername, $username]);
			return (0);
		}
		else
			return (2);
	}
	catch (PDOException $e) {
		echo 'Failed to modify username: ' . $e->getMessage();
		return (1);
	}
	$db = NULL;
}

function update_email($username, $email)
{
	require("config/database.php");
	try {
		$db = new PDO("mysql:host=$DB_DNS;port=8889;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = "SELECT `username` from `users` WHERE `username` = ?;";
		$stmt = $db->prepare($query);
		$stmt->execute([$username]);
		if ($stmt->fetchAll())
		{
			$query1 = "UPDATE `users` SET `email`= ? WHERE `username` = ?;";
			$stmt1 = $db->prepare($query1);
			$stmt1->execute([$email, $username]);
			return (0);
		}
		else
			return (2);
	}
	catch (PDOException $e) {
		echo 'Failed to modify email: ' . $e->getMessage();
		return (1);
	}
	$db = NULL;
}

function get_email($login)
{
	require("config/database.php");
	try {
		$db = new PDO("mysql:host=$DB_DNS;port=8889;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = "SELECT `email` from `users` where `username`= ?;";
		$stmt = $db->prepare($query);
		$stmt->execute([$login]);
		return ($stmt->fetchAll()[0]["email"]);
	}
	catch (PDOException $e) {
		echo 'Error while finding email: ' . $e->getMessage();
	}
	$db = NULL;
}

function get_images($login)
{
	require("config/database.php");
	try {
		$db = new PDO("mysql:host=$DB_DNS;port=8889;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = "SELECT `path`, `image_id` from `images` inner join `users` on images.image_user_id = users.user_id where `username`= ? order by `created` desc;";
		$stmt = $db->prepare($query);
		$stmt->execute([$login]);
		return ($stmt->fetchAll());
	}
	catch (PDOException $e) {
		echo 'Error while finding images: ' . $e->getMessage();
	}
	$db = NULL;
}

function get_all_images()
{
	require("config/database.php");
	try {
		$db = new PDO("mysql:host=$DB_DNS;port=8889;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = "SELECT `path`, `image_id`, users.username as user from `images` inner join `users` on users.user_id = images.image_user_id order by `created` desc;";
		$stmt = $db->prepare($query);
		$stmt->execute();
		return ($stmt->fetchAll());
	}
	catch (PDOException $e) {
		echo 'Error while finding images: ' . $e->getMessage();
	}
	$db = NULL;
}

function did_i_like_this($login, $image_id)
{
	require("config/database.php");
	try {
		$db = new PDO("mysql:host=$DB_DNS;port=8889;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = "SELECT `like_id` from `likes` inner join `users` on likes.like_user_id = users.user_id where `username`= ? AND `like_image_id` = ?;";
		$stmt = $db->prepare($query);
		$stmt->execute([$login, $image_id]);
		if ($stmt->fetchAll())
			return ("content/liked.png");
		else
			return ("content/like.png");
	}
	catch (PDOException $e) {
		echo 'Error while checking likes: ' . $e->getMessage();
	}
	$db = NULL;
}

function count_likes($image_id)
{
	require("config/database.php");
	try {
		$db = new PDO("mysql:host=$DB_DNS;port=8889;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = "SELECT COUNT(*) from `likes` where `like_image_id` = ?;";
		$stmt = $db->prepare($query);
		$stmt->execute([$image_id]);
		return ($stmt->fetchAll()[0][0]);
	}
	catch (PDOException $e) {
		echo 'Error while finding images: ' . $e->getMessage();
	}
	$db = NULL;
}

function like($login, $image_id)
{
	require("config/database.php");
	try {
		$db = new PDO("mysql:host=$DB_DNS;port=8889;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = "SELECT `like_user_id` from `likes` inner join `users` on likes.like_user_id = users.user_id where `username`= ? AND `like_image_id` = ?;";
		$stmt = $db->prepare($query);
		$stmt->execute([$login, $image_id]);
		if(!($res = $stmt->fetchAll()))
		{
			$query1 = "SELECT `user_id` from `users` where `username` = ?;";
			$stmt1 = $db->prepare($query1);
			$stmt1->execute([$login]);
			if ($res1 = $stmt1->fetchAll())
			{
				$query2 = "INSERT into `likes` (`like_user_id`, `like_image_id`)
				values (?, ?);";
				$stmt2 = $db->prepare($query2);
				$stmt2->execute([$res1[0]["user_id"], $image_id]);

				$query3 = "SELECT `username`, `email` from `users` inner join `images` on images.image_user_id = users.user_id where images.image_id = ? AND users.notification = 1;";
				$stmt3 = $db->prepare($query3);
				$stmt3->execute([$image_id]);
				if ($res2 = $stmt3->fetchAll())
				{
					$subject = "You have a new like!";
					$message = "
					Hi ".$res2[0]["username"]."!

					".$login." liked your photo!";
					$headers = "From:camagru <noreply@camagru.com>"."\r\n";
					mail($res2[0]["email"], $subject, $message, $headers);
				}
			}
		}
		else
		{
			$query1 = "DELETE from `likes` where `like_user_id` = ? AND `like_image_id` = ?;";
			$stmt1 = $db->prepare($query1);
			$stmt1->execute([$res[0]["like_user_id"], $image_id]);
		}
	}
	catch (PDOException $e) {
		echo 'Error while adding like: ' . $e->getMessage();
	}
	$db = NULL;
}

function notifications_off($username)
{
	require("config/database.php");
	try {
		$db = new PDO("mysql:host=$DB_DNS;port=8889;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = "UPDATE `users` SET `notification` = 0 where `username` = ?;";
		$stmt = $db->prepare($query);
		$stmt->execute([$username]);
	}
	catch (PDOException $e) {
		echo 'Error while editing notifications: ' . $e->getMessage();
	}
	$db = NULL;
}

function notifications_on($username)
{
	require("config/database.php");
	try {
		$db = new PDO("mysql:host=$DB_DNS;port=8889;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = "UPDATE `users` SET `notification` = 1 where `username` = ?;";
		$stmt = $db->prepare($query);
		$stmt->execute([$username]);
	}
	catch (PDOException $e) {
		echo 'Error while editing notifications: ' . $e->getMessage();
	}
	$db = NULL;
}

function notifications($username)
{
	require("config/database.php");
	try {
		$db = new PDO("mysql:host=$DB_DNS;port=8889;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = "SELECT `notification` from `users` where `username` = ?;";
		$stmt = $db->prepare($query);
		$stmt->execute([$username]);
		return ($stmt->fetchAll()[0]["notification"]);
	}
	catch (PDOException $e) {
		echo 'Error while checking notifications: ' . $e->getMessage();
	}
	$db = NULL;
}

function savephoto($username, $path)
{
	require("config/database.php");
	try {
		$db = new PDO("mysql:host=$DB_DNS;port=8889;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = "SELECT `user_id` from `users` where `username` = ?;";
		$stmt = $db->prepare($query);
		$stmt->execute([$username]);
		$res = $stmt->fetchAll();
		$query2 = "INSERT into `images` (`image_user_id`, `created`,`path`) values (?, ?, ?);";
		$stmt2 = $db->prepare($query2);
		$stmt2->execute([$res[0]["user_id"], date("Y-m-d H:i:s"), $path]);
	}
	catch (PDOException $e) {
		echo 'Error while adding image to database: ' . $e->getMessage();
	}
	$db = NULL;
}

function delete_img($username, $img_id)
{
	require("config/database.php");
	try {
		$db = new PDO("mysql:host=$DB_DNS;port=8889;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = "SELECT images.path, users.user_id from `images` inner join `users` on users.user_id = images.image_user_id where users.username = ? AND images.image_id = ?;";
		$stmt = $db->prepare($query);
		$stmt->execute([$username, $img_id]);
		$res = $stmt->fetchAll();
		if ($res)
		{
			$query1 = "DELETE from `images` where `image_user_id` = ? AND `image_id` = ?;";
			$stmt1 = $db->prepare($query1);
			$stmt1->execute([$res[0]["user_id"], $img_id]);
			$query2 = "DELETE from `likes` where `like_image_id` = ?;";
			$stmt2 = $db->prepare($query2);
			$stmt2->execute([$img_id]);
			return ($res[0]["path"]);
		}
		return (NULL);
	}
	catch (PDOException $e) {
		echo 'Error while deleting an image: ' . $e->getMessage();
	}
	$db = NULL;
}

?>