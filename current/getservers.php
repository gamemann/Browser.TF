<?php
	/* Options. */
	$maxServers = 100000;
	
	if (isset($_POST['maxServers']))
	{
		$maxServers = $_POST['maxServers'];
	}
	
	$filter = '\\appid\\440\\nor\\1\\white\\1';
	
	if (isset($_POST['filter']))
	{
		$filter = $_POST['filter'];
	}
	
	$url = "https://api.steampowered.com/IGameServersService/GetServerList/v1/?limit=" . $maxServers . "&key=94D0309C9AE51FB41FBD86215447959C&filter=" . $filter;
	
	$curl = curl_init();
	curl_setopt_array($curl, array
	(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $url,
		CURLOPT_SSL_VERIFYPEER => false
	));
	
	$results = curl_exec($curl);
	if (!$results)
	{
		die(curl_error($curl));
	}
	
	curl_close($curl);
	
	$results = iconv('UTF-8', 'UTF-8//IGNORE', utf8_encode($results));
	
	$file = fopen('servers.json', 'w');
	
	if ($file)
	{
		fwrite($file, $results);
		
		fclose($file);
	}
?>