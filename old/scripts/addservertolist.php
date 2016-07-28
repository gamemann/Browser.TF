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
	
	// Source Query.
	require_once($_SERVER['DOCUMENT_ROOT'] . '/SourceQuery/SourceQuery.class.php');
	
	// User's information.
	$userInfo = $M['users']->getUserInfo();
	
	// Let's start!
	$success = false;
	
	// APPID
	$appID = $M['browser']->getGameID();
	
	if (isset($_GET['message']))
	{
		$message = $_GET['message'];
	}
	else
	{
		$message = '';
	}
	
	// Add the server to the requests section if available.
	if (isset($_POST['ip']) && !empty($_POST['ip']) && isset($_POST['port']) && $userInfo)
	{
		$serverIP = $_POST['ip'];
		$serverPort = $_POST['port'];
		
		// Now create the query.
		$squery = new SourceQuery();
		$continue = false;
		$serverInfo = false;
		
		try
		{
			$squery->Connect($serverIP, $serverPort, 1);
			$serverInfo = $squery->GetInfo();
		}
		catch (Exception $e)
		{
		
		}
		
		if ($serverInfo)
		{
			if ($serverInfo['MaxPlayers'] > 0)
			{
				if($serverInfo['AppID'] == $appID)
				{
					$continue = true;
				}
				else
				{
					$message = 'Server\'s App ID isn\'t ' . $appID . ' (' . $serverInfo['AppID'] . ')';
				}
			}
			else
			{
				$message = 'Server is offline.';
			}
		}
		else
		{
			$message = 'Server information is invalid.';
		}
		
		if ($continue)
		{
			/* Prepare the query. */
			$db->where('ip', $serverIP);
			$db->where('port', $serverPort);
			
			/* Execute the query. */
			$check = $db->get('`' . $appID . '-servers`');
			
			/* Check the row count. */
			if ($db->count < 1)
			{
				$newData = $M['main']->json_readable_encode($serverInfo);
				
				/* Prepare the query. */
				$data = Array
				(
					'ip' => $serverIP,
					'port' => $serverPort,
					'info' => $newData,
					'online' => 1
				);
				
				/* Execute the query. */
				$query = $db->insert('`' . $appID . '-servers`', $data);
				
				if ($query)
				{
					$success = true;
				}
				else
				{
					$message = 'Error inserting into database. Error: ' . $db->getLastError();
				}
			}
			else
			{
				// Already exist.
				$message = 'Server already exist in list.';
			}
		}
	}
	else
	{
		$message = 'Not signed in...';
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