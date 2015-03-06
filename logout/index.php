<?php

	/* Loading Files */
	require_once ('../assets/php/load.php');

	UserLogout();
	header("Location: " . $_SERVER['HTTP_REFERER']);
?>