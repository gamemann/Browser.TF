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
	
	// Let's start!
	$success = false;
	
	if (isset($_GET['message']))
	{
		$message = $_GET['message'];
	}
	else
	{
		$message = '';
	}
	
	// Add the server to the requests section if available.
	if (isset($_POST['ip']) && !empty($_POST['ip']) && isset($_POST['port']) && !empty($_POST['port']) && isset($_POST['reason']) && !empty($_POST['reason']) && $userInfo)
	{
		$ip = $_POST['ip'];
		$port = $_POST['port'];
		$reason = $_POST['reason'];
		
		/* Prepare the query. */
		$data = Array
		(
			'aid' => $userInfo['id'],
			'ip' => $ip,
			'port' => $port,
			'dateadded' => time(),
			'reason' => nl2br($reason)
		);
		
		/* Execute the query. */
		$check = $db->insert('reports', $data);
		
		/* Check the query. */
		if ($check)
		{
			$success = true;
		}
		else
		{
			$message = 'Server not successfully reported. (Error: ' . $db->getLastError() . '). Please contact an Administrator.';
		}
	}
	
	if ($success)
	{
		echo '1';
	}
	else
	{
		echo $message;
	}
?>