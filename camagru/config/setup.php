<?php

require_once("database.php");

try	{
	$db = new PDO("mysql:host=$DB_DNS;port=8889;", $DB_USER, $DB_PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "CREATE DATABASE IF NOT EXISTS $DB_NAME;";
	$stmt = $db->prepare($sql);
	$stmt->execute();
}
catch (PDOException $e) {
	echo 'Connection failed: ' . $e->getMessage().PHP_EOL;
}
$db = NULL;

try	{
	$db = new PDO("mysql:host=$DB_DNS;port=8889;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "CREATE TABLE IF NOT EXISTS `users`(
		`user_id` INT(10) AUTO_INCREMENT PRIMARY KEY NOT NULL,
		`username` VARCHAR(255) NOT NULL,
		`email` VARCHAR(255) NOT NULL,
		`password` VARCHAR(255) NOT NULL,
		`hash` VARCHAR(32) NOT NULL,
		`verified` INT(1) NOT NULL DEFAULT '0',
		`notification` INT(1) NOT NULL DEFAULT '1',
		`reset` VARCHAR(32) NOT NULL
		);";
	$stmt = $db->prepare($sql);
	$stmt->execute();
}
catch (PDOException $e) {
	echo 'Failed to create a table: ' . $e->getMessage().PHP_EOL;
}
$db = NULL;

try	{
	$db = new PDO("mysql:host=$DB_DNS;port=8889;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "CREATE TABLE IF NOT EXISTS `images`(
		`image_user_id` INT NOT NULL,
		`created` TIMESTAMP NOT NULL,
		`path` VARCHAR(255) NOT NULL,
		`image_id` INT(10) AUTO_INCREMENT PRIMARY KEY NOT NULL
		);";
	$stmt = $db->prepare($sql);
	$stmt->execute();
}
catch (PDOException $e) {
	echo 'Failed to create a table: ' . $e->getMessage().PHP_EOL;
}
$db = NULL;

try	{
	$db = new PDO("mysql:host=$DB_DNS;port=8889;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "CREATE TABLE IF NOT EXISTS `likes`(
		`like_user_id` INT NOT NULL,
		`date` TIMESTAMP NOT NULL,
		`like_id` INT(10) AUTO_INCREMENT PRIMARY KEY NOT NULL,
		`like_image_id` INT NOT NULL
		);";
	$stmt = $db->prepare($sql);
	$stmt->execute();
}
catch (PDOException $e) {
	echo 'Failed to create a table: ' . $e->getMessage().PHP_EOL;
}
$db = NULL;

try	{
	$db = new PDO("mysql:host=$DB_DNS;port=8889;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "CREATE TABLE IF NOT EXISTS `comments`(
		`comment_user_id` INT NOT NULL,
		`date` TIMESTAMP NOT NULL,
		`comment_id` INT(10) AUTO_INCREMENT PRIMARY KEY NOT NULL,
		`comment_image_id` INT NOT NULL,
		`comment` VARCHAR(255) NOT NULL
		);";
	$stmt = $db->prepare($sql);
	$stmt->execute();
}
catch (PDOException $e) {
	echo 'Failed to create a table: ' . $e->getMessage().PHP_EOL;
}
$db = NULL;

if (!file_exists("../images/"))
	mkdir("../images/");

?>