<?php
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
	
	$ip = 0;
	if (isset($_POST['ip']))
	{
		$ip = $_POST['ip'];
	}	
	
	$port = 0;
	if (isset($_POST['port']))
	{
		$port = $_POST['port'];
	}	
	
	$rating = 1;
	if (isset($_POST['rating']))
	{
		$rating = $_POST['rating'];
	}	
	
	$review = 0;
	if (isset($_POST['review']))
	{
		$review = $_POST['review'];
	}
	
	// Now what? Well, add the review.
	
	if ($userInfo && $ip && $port && !empty($review))
	{
		/* Prepare the query. */
		$data = Array
		(
			'aid' => $userInfo['id'],
			'ip' => $ip,
			'port' => $port,
			'rating' => $rating,
			'review' => $review,
			'timeadded' => time()
		);
		
		/* Execute the query. */
		$check = $db->insert('ratings', $data);

		if ($check)
		{
			echo '1';
		}
		else
		{
			echo '0 (' . $db->getLastError() . ')';
		}
	}
	
?>