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
	
	$message = '';
	
	// Add the server to the requests section if available.
	if (isset($_POST['title']) && !empty($_POST['title']) && isset($_POST['content']) && !empty($_POST['content']) && $M['users']->hasGroupAccess($userInfo, 'AP-Announcements'))
	{
		$title = $_POST['title'];
		$description = $_POST['description'];
		$content = $_POST['content'];
		
		/* Prepare the query. */
		$data = Array
		(
			'uid' => $userInfo['id'],
			'title' => $title,
			'description' => $description,
			'content' => $content,
			'dateadded' => time()
		);
		
		/* Execute the query. */
		$id = $db->insert('announcements', $data);
		
		if ($id)
		{
			$success = true;
		}
		else
		{
			$message = $db->getLastError();
		}
	}
	else
	{
		$message = 'No access';
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