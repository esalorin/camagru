<?php

function valid_password($password):bool
{
	$uppercase = preg_match('@[A-Z]@', $password);
	$lowercase = preg_match('@[a-z]@', $password);
	$number    = preg_match('@[0-9]@', $password);
	$specialChars = preg_match('@[^\w]@', $password);
	if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8)
		return FALSE;
	return TRUE;
}

?>