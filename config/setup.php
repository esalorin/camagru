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

if (!file_exists("images"))
	mkdir("images");

try	{
	$pw1 = hash("whirlpool", 'randompassword');
	$pw2 = hash("whirlpool", 'fakepassword');
	$pw3 = hash("whirlpool", 'falsepassword');
	$db = new PDO("mysql:host=$DB_DNS;port=8889;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "INSERT into `users` (`username`, `email`, `password`, `hash`, `reset`, `verified`) 
	values ('uuser1', 'randomemail@camagru.com', ?, 1, 2, 1),
	('fakeUser', 'fakeemail@camagru.com', ?, 2, 3, 1),
	('watermelonZug4r', 'watermelonZug4r@random.com', ?, 3, 4, 1);";
	$stmt = $db->prepare($sql);
	$stmt->execute([$pw1, $pw2, $pw3]);
}
catch (PDOException $e) {
	echo 'Failed to add users: ' . $e->getMessage().PHP_EOL;
}
$db = NULL;

try	{
	$db = new PDO("mysql:host=$DB_DNS;port=8889;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "INSERT into `images` (`image_user_id`, `created`,`path`) 
	values (1, '2021-08-30 16:15:50', 'images/60a52e7998035.png'),
	(1, '2021-08-31 12:15:10', 'images/60a53426c9a05.png'),
	(1, '2021-08-30 16:15:50', 'images/60ad06f2295e4.png'),
	(1, '2021-09-01 16:15:50', 'images/60ad056e66d23.png'),
	(2, '2021-08-30 16:15:50', 'images/60af8b96ef065.png'),
	(2, '2021-09-02 04:55:50', 'images/6139f6c747d0f.png'),
	(2, '2021-09-02 20:40:50', 'images/60a523a309b90.png'),
	(3, '2021-08-30 09:19:00', 'images/60ad068eb3d20.png'),
	(3, '2021-09-01 15:15:50', 'images/60af8ad2bf673.png'),
	(3, '2021-09-02 22:15:50', 'images/60aff945effc6.png'),
	(3, '2021-09-03 19:53:50', 'images/60a7ccd06d603.png');";
	$stmt = $db->prepare($sql);
	$stmt->execute();
}
catch (PDOException $e) {
	echo 'Failed to add images: ' . $e->getMessage().PHP_EOL;
}
$db = NULL;

try	{
	$db = new PDO("mysql:host=$DB_DNS;port=8889;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "INSERT into `likes` (`like_user_id`, `like_image_id`)
	values (1, 1),
	(1, 2),
	(1, 3),
	(1, 5),
	(1, 8),
	(2, 1),
	(2, 2),
	(2, 4),
	(2, 5),
	(2, 6),
	(2, 7),
	(2, 8),
	(2, 9),
	(2, 10),
	(2, 11),
	(3, 1),
	(3, 3),
	(3, 4),
	(3, 5),
	(3, 6),
	(3, 7),
	(3, 8),
	(3, 10),
	(3, 11);";
	$stmt = $db->prepare($sql);
	$stmt->execute();
}
catch (PDOException $e) {
	echo 'Failed to add likes: ' . $e->getMessage().PHP_EOL;
}
$db = NULL;

?>