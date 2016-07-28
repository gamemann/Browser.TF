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
	
	// Source Query.
	require_once($_SERVER['DOCUMENT_ROOT'] . '/SourceQuery/SourceQuery.class.php');
	
			
	// Now create the query.
	$squery = new SourceQuery();
	
	try
	{
		$squery->Connect('104.255.67.178', 27015, 1);
		$serverInfo = $squery->GetInfo();
	}
	catch (Exception $e)
	{
	
	}
			
	$data = $M['main']->json_readable_encode($serverInfo);
	
	echo $data;
	
	echo '<br /><br />';
	
	$array = array
	(
		'Tons of shit\'s on my post\'s',
		'And using "qotes" does "nothing\' at all!'
	);
	
	$toJSON = json_encode($array);
	echo $toJSON;
	
	// Now watch.
	$blah = json_decode(stripcslashes($toJSON), true);
	echo '<br /><br />Now:<br />';
	echo str_replace('\\\'', '\'', $toJSON);
	
	echo '<br />Output:<br />';
	
	print_r($blah, false);
	
	
?>