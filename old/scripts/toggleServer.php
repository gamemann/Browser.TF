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
	if (isset($_GET['ip']))
	{
		$ip = $_GET['ip'];
	}	
	
	$port = 0;
	if (isset($_GET['port']))
	{
		$port = $_GET['port'];
	}
	
	$action = '';
	if (isset($_GET['action']))
	{
		$action = $_GET['action'];
	}
	
	if ($userInfo && ($userInfo['group'] == 2 || $userInfo['group'] == 3) && $ip && !empty($action))
	{
		if ($action == 'disable')
		{
			/* Prepare the query. */
			$db->where('ip', $ip);
			$db->where('port', $port);
			
			$data = Array
			(
				'enabled' => 0
			);
			
			/* Execute the query. */
			$check = $db->update('approved_servers', $data);
			
			if ($check)
			{	
				echo '1';
			}
			else
			{
				echo '0';
			}
		}
		elseif ($action == 'enable')
		{
			/* Prepare the query. */
			$db->where('ip', $ip);
			$db->where('port', $port);
			
			$data = Array
			(
				'enabled' => 1
			);
			
			/* Execute the query. */
			$check = $db->update('approved_servers', $data);
			
			if ($check)
			{	
				echo '1';
			}
			else
			{
				echo '0';
			}
		}
	}
	
?>