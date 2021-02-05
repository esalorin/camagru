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

			$url = "http://localhost:8888/camagru/verify.php?email=".$email."&hash=".$hash;
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

			$url = "http://localhost:8888/camagru/reset.php?email=".$email."&reset=".$reset;
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
		return ($stmt->fetchAll()[0][email]);
	}
	catch (PDOException $e) {
		echo 'Didn\'t find email: ' . $e->getMessage();
	}
	$db = NULL;
}

?>