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
	
	$id = 0;
	if (isset($_GET['id']))
	{
		$id = $_GET['id'];
	}
	
	$action = '';
	if (isset($_GET['action']))
	{
		$action = $_GET['action'];
	}
	
	// Now what? Well, promote/demote the user!
	
	if ($userInfo && $userInfo['group'] == 3 && $id && !empty($action))
	{
		if ($action == 'promote')
		{
			/* Prepare the query. */
			$db->where('id', $id);
			
			$data = Array
			(
				'group' => 2
			);
			
			/* Execute the query. */
			$check = $db->update('accounts', $data);
			
			if ($check)
			{	
				/* Insert into the Admin logs. */
				
				echo '1';
			}
			else
			{
				echo '0';
			}
		}
		elseif ($action == 'demote')
		{
			/* Prepare the query. */
			$db->where('id', $id);
			
			$data = Array
			(
				'group' => 1
			);
			
			/* Execute the query. */
			$check = $db->update('accounts', $data);
			
			if ($check)
			{	
				/* Insert into the Admin logs. */
				
				echo '1';
			}
			else
			{
				echo '0';
			}
		}
	}
	
?>