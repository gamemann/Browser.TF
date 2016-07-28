<?php
	/* Configuration. */
	$tags = Array
	(
		"stock" => Array
		(
			"display" => "Stock",
			"type" => "success"
		),			
		
		"vanilla" => Array
		(
			"display" => "Vanilla",
			"type" => "success"
		),		
		
		"trade" => Array
		(
			"display" => "Trade",
			"type" => "danger"
		),
	);
	
	$minServers = 500;
	
	$results = false;
	$newArray = Array
	(
		"draw" => 1,
		"recordsTotal" => 0,
		"recordsFiltered" => 0,
		"data" => Array()
	);
	$file = 'servers.json';
	$handle = fopen($file, 'r');
	
	if ($handle)
	{
		$results = fread($handle, filesize($file));
		fclose($handle);
	}
	
	if ($results)
	{
		$servers = json_decode($results, false);
		
		foreach ($servers->response->servers as $server)
		{
			if (!isset($server->map))
			{
				continue;
			}
			
			/* Custom variables. */
			$customHostname = $server->name;
			$gametype = (isset($server->gametype)) ? $server->gametype : '';
			$region = (isset($server->region)) ? $server->region : 255;
			$secure = ($server->secure) ? 1 : 0;
			$onlyIP = explode(":", $server->addr);
			
			$onlyIP = $onlyIP[0];
			
			/* Check for tags. */
			$tagCount = 0;
			$maxTags = 2;
			$tag = '';
			
			foreach ($tags as $key => $value)
			{
				if (!isset($server->gametype))
				{
					break;
				}
				
				if ($tagCount > $maxTags)
				{
					break;
				}
				
				if (stristr($server->gametype, $key) || stristr($server->name, $key))
				{
					$tag .= '<span class="label label-' . $value['type'] . '">' . $value['display'] . '</span> ';
					$tagCount++;
				}
			}
			
			$customHostname = $tag . ' ' . $server->name;
			
			$newArray["data"][] = Array
			(
				$customHostname,
				$onlyIP,
				$server->gameport,
				$server->players,
				$server->max_players,
				$server->map,
				'<a href="steam://connect/' . $server->addr . '/"><button type="button" class="btn btn-primary">Connect!</button></a>',
				$gametype,
				$region,
				$secure
			);
		}
		
		$newArray["recordsTotal"] = count($newArray["data"]);
		$newArray["recordsFiltered"] = count($newArray["data"]);
	}
	if (count($servers->response->servers) < $minservers)
	{
		echo 'Servers count under minimum servers variable. Aborting.<br />';
	}
	else
	{
		$json = json_encode($newArray);
		$handle = fopen('serversToDT.json', 'w');
		
		if ($handle)
		{
			fwrite($handle, $json);
			fclose($handle);
		}
	}
	
	echo count($servers->response->servers) . ' listed...';
?>