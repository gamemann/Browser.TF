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
	if (isset($_POST['ip']) && !empty($_POST['ip']) && isset($_POST['port']) && !empty($_POST['port']) && isset($_POST['email']) && !empty($_POST['email'])	&& $userInfo)
	{
		$serverIP = $_POST['ip'];
		$serverPort = $_POST['port'];
		$serverLocation = $_POST['location'];
		$email = $_POST['email'];
		$other = $_POST['description'];
		
		/* Prepare the query. */
		$db->where('ip', $serverIP);
		$db->where('port', $serverPort);
		
		/* Execute the query. */
		$check = $db->get('servers');
		
		/* Check the row count. */
		if ($db->count < 1)
		{
			/* Prepare the query. */
			$db->where('ip', $serverIP);
			$db->where('port', $serverPort);
			
			/* Execute the query. */
			$check2 = $db->get('requests');
			
			if ($db->count < 1)
			{
				/* Prepare the query. */
				$data = Array
				(
					'aid' => $userInfo['id'],
					'ip' => $serverIP,
					'port' => $serverPort,
					'location' => $serverLocation,
					'email' => $email,
					'other' => $other,
					'timeadded' => time()
				);
				
				/* Execute the query. */
				$query = $db->insert('requests', $data);
				
				/* Check the query. */
				if ($query)
				{
					$message = 'Server successfully added! The server will need to be approved by Administrators. We will contact you via email.';
					$success = true;
					$M['browser']->notifyMail(1, 'Server Add Requested (' . $serverIP . ':' . $serverPort . ')', 'A server is being requested to be apart of the Browser.TF list.<br />IP: ' . $serverIP . '<br />Port: ' . $serverPort);
				}
				else
				{
					$message = 'Server not successfully added. Error: "' . $db->getLastError() . '". Please contact us with this error.'; 
				}
			}
			else
			{
				$message = 'This server has already been requested.';
			}
		}
		else
		{
			$message = 'This server is already in the database.';
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