<?php
	// Let's start!
	session_start();
	
	// Include the main file. Let's start!
	require_once($_SERVER['DOCUMENT_ROOT'] . '/init.php');
	
	// Get the DataBase.
	$db = $M['mysql']->receiveDataBase();
	
	// Set the time-zone to New York (for myself).
	date_default_timezone_set('America/New_York');
	
	/* CUSTOM THINGS */
	
	// SteamAuth.
	require_once($_SERVER['DOCUMENT_ROOT'] . '/steamauth/steamauth.php');
	
	// Steam Condenser
	require_once($_SERVER['DOCUMENT_ROOT'] . '/condenser/steam-condenser.php');
	
	// User's information.
	$userInfo = $M['users']->getUserInfo();
	
	$appID = $_POST['gameID'];
	
	setcookie('appid', $appID, time() + 300000, '/');
?>