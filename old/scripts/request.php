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
	
	$action = '';
	if (isset($_GET['action']))
	{
		$action = $_GET['action'];
	}	
	
	$notes = '';
	if (isset($_GET['notes']))
	{
		$notes = $_GET['notes'];
	}
	
	$id = 0;
	if (isset($_GET['id']))
	{
		$id = $_GET['id'];
	}
	
	// Optional
	$serverIP = '';
	if (isset($_GET['ip']))
	{
		$serverIP = $_GET['ip'];
	}	
	
	$serverPort = '';
	if (isset($_GET['port']))
	{
		$serverPort = $_GET['port'];
	}
	
	if ($userInfo && ($userInfo['group'] == 2 || $userInfo['group'] == 3) && $id && !empty($action))
	{
		if ($action == 'add' && !empty($serverIP) && !empty($serverPort))
		{
			/* Prepare the query. */
			$data = Array
			(
				'ip' => $serverIP,
				'port' => $serverPort,
				'dateadded' => time(),
				'enabled' => 1
			);
			
			/* Execute the query. */
			$check = $db->insert('approved_servers', $data);
			
			/* Check the query. */
			if ($check)
			{
				echo '1';
				
				/* Update the request. */
				
				/* Prepare the query. */
				$db->where('id', $id);
				
				$data = Array
				(
					'status' => 1,
					'notes' => $notes
				);
				
				/* Execute the query. */
				$db->update('requests', $data);
			}
			else
			{
				echo '0';
			}
		}
		elseif ($action == 'remove')
		{
			/* Prepare the query. */
			$db->where('id', $id);
			
			$data = Array
			(
				'status' => 2,
				'notes' => $notes
			);
			
			/* Execute the query. */
			$check = $db->update('requests', $data);
			
			/* Check the query. */
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
	else
	{
		echo 'No Access. (' . $userInfo['group'] . ', ' . $id . ', "' . $action . '")';
	}
	
?>