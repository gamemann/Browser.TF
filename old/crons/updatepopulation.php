<?php
	// Let's start!
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
	
	// Source Query.
	require_once($_SERVER['DOCUMENT_ROOT'] . '/SourceQuery/SourceQuery.class.php');
	
	// Steam Condenser.
	require_once($_SERVER['DOCUMENT_ROOT'] . '/condenser/steam-condenser.php');
	
	// Now for the custom code!
	
	// Config
	$serversamount = 20;
	
	$query = $db->query("SELECT * FROM `owned_servers` ORDER BY `lastupdated` ASC LIMIT 0, " . $serversamount);

	if ($query && $query->num_rows > 0)
	{
		while ($row = $query->fetch_assoc())
		{
			$squery = new SourceQuery();
			$serverInfo = 0;
			
			try
			{
				$squery->Connect($row['ip'], $row['port'], 1);
				$serverInfo = $squery->GetInfo();
			}
			catch (Exception $e)
			{
			
			}
			
			$online = 1;
			
			if (!$serverInfo)
			{
				// Server is offline.
				$online = 0;
				$serverInfo['Players'] = 0;
				$serverInfo['MaxPlayers'] = 0;
				$serverInfo['Map'] = '';
			}
			
			$insertQuery = $db->query("INSERT INTO `population` (`ip`, `port`, `online`, `population`, `populationmax`, `map`, `timestamp`) VALUES ('" . $row['ip'] . "', " . $row['port'] . ", " . $online . ", " . $serverInfo['Players'] . ", " . $serverInfo['MaxPlayers'] . ", '" . $serverInfo['Map'] . "', " . time () . ");");
			
			if (!$insertQuery)
			{
				echo $db->error;
			}
			
			// Now simply update the `lastupdated` mark.
			$db->query("UPDATE `owned_servers` SET `lastupdated`=" . time() . " WHERE `ip`='" . $row['ip'] . "' AND `port`=" . $row['port']);
			
			$squery->Disconnect();
		}
	}
?>