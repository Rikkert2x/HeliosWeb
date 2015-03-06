<?php

	session_start ();
	ob_start ();

	/* Loading Files */
	require_once ('Settings.php');
	require_once ('functions.php');
	
	$Member = UserLoggedIn ();

?>