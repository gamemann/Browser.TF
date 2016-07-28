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
	
	// APPID
	$appID = $M['browser']->getGameID();
	
	$servers = false;
	error_reporting(E_ERROR);
	$master = new MasterServer(MasterServer::SOURCE_MASTER_SERVER);
	try
	{
		$servers = $master->getServers(MasterServer::REGION_ALL, "\\appid\\{$appID}\\name_match\\*Valve*", true);
	}
	catch (TimeoutException $e)
	{
		echo 'TIMED OUT<br />';
	}
	
	if ($servers)
	{	
		if (count($servers) > 0)
		{
			$data = json_encode($servers);
			file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/master/' . $appID . '_valvelist.json', $data);
			echo 'List Updated.';
		}
		else
		{
			echo 'List not updated. Server count is less than 1';
		}
	}
	else
	{
		echo 'List not updated.';
	}

?>