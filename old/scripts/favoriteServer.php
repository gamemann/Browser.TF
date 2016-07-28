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
	
	// User's information.
	$userInfo = $M['users']->getUserInfo();
	
	// APPID
	$appID = $M['browser']->getGameID();
	
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
	
	// Now what? Well, add the server to the favorites list.
	
	if ($userInfo && $ip && !empty($action))
	{
		if ($action == 'favorite')
		{
			/* Prepare the query. */
			$data = Array
			(
				'aid' => $userInfo['id'],
				'ip' => $ip,
				'port' => $port,
				'timeadded' => time(),
				'appid' => $appID
			);
			
			/* Execute the query. */
			$check = $db->insert('favorites', $data);
			
			if ($check)
			{
				echo '1';
			}
			else
			{
				echo '0';
			}
		}
		elseif ($action == 'unfavorite')
		{
			/* Prepare the query. */
			$db->where('aid', $userInfo['id']);
			$db->where('ip', $ip);
			$db->where('port', $port);
			$db->where('appid', $appID);
			
			/* Execute the query. */
			$check = $db->delete('favorites');
			
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