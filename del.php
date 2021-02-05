<?php

require_once("config/database.php");

try	{
	$db = new PDO("mysql:host=$DB_DNS;port=8889;", $DB_USER, $DB_PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "DROP DATABASE $DB_NAME;";
	$stmt = $db->prepare($sql);
	$stmt->execute();
}
catch (PDOException $e) {
	echo 'Connection failed: ' . $e->getMessage().PHP_EOL;
}
$db = NULL;
?>