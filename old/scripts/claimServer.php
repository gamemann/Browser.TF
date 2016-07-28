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
	
	// APPID
	$appID = $M['browser']->getGameID();
	
	error_reporting(E_ERROR);
	
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
	
	// Now what? Well, add the review.
	
	if ($userInfo && $ip && $port)
	{
		
		$squery = new SourceServer($ip, $port);
		
		try
		{
			$squery->initialize();
			$serverInfo = $squery->getServerInfo();
		}
		catch (TimeoutException $e)
		{
		
		}
		
		
		if ($serverInfo['serverName'] == 'Browser.TF')
		{
		
			/* Prepare the query. */
			$db->where('ip', $ip);
			$db->where('port', $port);
			
			/* Execute the query. */
			$favorites = $db->get('owned_servers');
			
			/* Check the row count. */
			if ($db->count > 0)
			{
				/* Update owner. */
				
				/* Prepare query. */
				$db->where('ip', $ip);
				$db->where('port', $port);
				
				$data = Array
				(
					'aid' => $userInfo['id'],
					'appid' => $appID
					
				);
				
				/* Execute the query. */
				$check = $db->update('owned_servers', $data);
				
				/* Check the result. */
				if ($check)
				{
					echo '1';
				}
				else
				{
					echo 'Error: ' . $db->getLastError() . '';
				}
			}
			else
			{
				/* Insert owner. */
				
				/* Prepare query. */				
				$data = Array
				(
					'aid' => $userInfo['id'],
					'ip' => $ip,
					'port' => $port,
					'appid' => $appID
					
				);
				
				/* Execute the query. */
				$check = $db->insert('owned_servers', $data);
				
				/* Check the result. */
				if ($check)
				{
					echo '1';
				}
				else
				{
					echo 'Error: ' . $db->getLastError() . '';
				}
			}
		}
		else
		{
			echo 'Error: Incorrect Host Name';
		}
	}
	
?>