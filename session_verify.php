<?php

require_once("db_functions.php");

function verify_session($login, $passwd):bool
{
	if (!$login || $login == "" || !$passwd || $passwd == "")
		return FALSE;
	session_start();
	if (check_user($login, $passwd) == 0)
		return TRUE;
	return FALSE;
}

?>
