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
	
	// Servers Method
	if (isset($_GET['serversmethod']))
	{
		$value = $_GET['serversmethod'];
		
		if ($value < 2 && $value > -1)
		{
			// Set the cookie.
			setcookie('serversmethod', $value, time() + 31556952, '/'); 
		}
	}
	
	// Now, we can set up the page!
	
	$min = $_GET['min'];
	$max = $_GET['max'];
	$table = $_GET['browser'];
	
	$printArray = array();
	
	// Now retrieve all the servers and put them into the table.
	if ($table == 'fullbrowser' || $table == 'serverstable')
	{
		if ($M['browser']->getServersPullMethod() == 0)
		{
			$data = file_get_contents('../master/440.json');
			$data = json_decode($data, true);
		}
		elseif ($M['browser']->getServersPullMethod() == 1)
		{
			$queryStr = "SELECT * FROM `manualinfo` WHERE `online`=1";
			if ($max > 0)
			{
				$queryStr .= " LIMIT " . $min . "," . $max;
			}
			
			$query = $db->query($queryStr);
			
			if ($query)
			{
				while ($row = $query->fetch_assoc())
				{
					$data[] = array($row['ip'], $row['port'], $row['info']);
				}
			}
		}
	}
	elseif ($table == 'stocktable')
	{
		$query = $db->query("SELECT * FROM `approved_servers` WHERE `enabled`=1");
		
		if ($query)
		{
			while ($row = $query->fetch_assoc())
			{
				$data[] = array($row['ip'], $row['port']);
			}
		}
	}
	elseif ($table == 'favoritestable' && $userInfo)
	{
		$query = $db->query("SELECT * FROM `favorites` WHERE `aid`=" . $userInfo['id']);
		
		if ($query)
		{
			while ($row = $query->fetch_assoc())
			{
				$data[] = array($row['ip'], $row['port']);
			}
		}
	}
	elseif ($table == 'myserverstable' && $userInfo)
	{
		$query = $db->query("SELECT * FROM `owned_servers` WHERE `aid`=" . $userInfo['id']);
		
		if ($query)
		{
			while ($row = $query->fetch_assoc())
			{
				$data[] = array($row['ip'], $row['port']);
			}
		}
	}
	
	if (is_array($data))
	{
		$i = 0;
		
		foreach ($data as $server)
		{
			$i++;
			
			if ($max != 0 && ($min > $i || $max < $i))
			{
				continue;
			}
			
			$serverInfo = false;
			
			if ($M['browser']->getServersPullMethod() == 0)
			{
				if ($table == 'fullbrowser')
				{
					$info = explode(":", $server);
				}
				else
				{
					$info[0] = $server[0];
					$info[1] = $server[1];
				}
				
				$squery = new SourceQuery();

				try
				{
					$squery->Connect($info[0], $info[1], 1);
					$serverInfo = $squery->GetInfo();
				}
				catch (Exception $e)
				{
				
				}
			}
			else
			{

				$info[0] = $server[0];
				$info[1] = $server[1];
				$info[2] = $server[2];
				
				if ($M['browser']->getStoreType() == 0)
				{
					if ($table == 'fullbrowser')
					{
						$serverInfo = json_decode($info[2], true);
					}
					else
					{
						if (isset($server[2]))
						{
							$serverInfo = json_decode($server[2], true);
						}
						else
						{
							$query = $db->query("SELECT * FROM `manualinfo` WHERE `ip`='" . $info[0] . "' AND `port`=" . $info[1]);
							
							if ($query)
							{
								while ($row = $query->fetch_assoc())
								{
									$serverInfo = json_decode($row['info'], true);
								}
							}
						}
					}
				}
				else
				{
					$fileName = '../servers/' . $info[0] . ':' . $info[1] . '.txt';
					
					$hHndl = fopen($fileName, 'r+');
					if ($hHndl)
					{
						$serverInfo = fread($hHndl, filesize($fileName));

						if ($serverInfo)
						{
							$serverInfo = json_decode($serverInfo, true);
						}
						
						fclose($hHndl);
					}
					else
					{
						$serverInfo = false;
					}
				}
			}
			
			if ($serverInfo && $serverInfo['MaxPlayers'] > 0 && !empty($serverInfo['Map']) && !stristr($serverInfo['HostName'], 'Valve'))
			{
				if ($userInfo)
				{
					if ($M['browser']->isFavorited($userInfo, $info[0], $info[1]))
					{
						$toFav = '<span class="glyphicon glyphicon-star custom-unfav-star" title="Un-Favorite" onclick="unFavoriteServer(\\\'' . $info[0] . '\\\', ' . $info[1] . ', 1, ' . $i . ');"></span>';
					}
					else
					{
						$toFav = '<span class="glyphicon glyphicon-star custom-fav-star" title="Favorite" onclick="favoriteServer(\\\'' . $info[0] . '\\\', ' . $info[1] . ', 1, ' . $i . ');"></span>';
					}
				}
				else
				{
					$toFav = '';
				}
				
				// Add Server Row.
				$rating = $M['browser']->calculateRating($info[0], $info[1], array('population'));
				
				// Add Server Row.
				echo '<script>addServerRow(\'<a href="/pages/servers/viewserver.php?ip=' . $info[0] . '&port=' . $info[1] . '">' . addslashes($serverInfo['HostName']) . '</a>\', ' . $serverInfo['Players'] . ', ' . $serverInfo['MaxPlayers'] . ', \'' . addslashes($serverInfo['Map']) . '\', \'' . $info[0] . '\', ' . $info[1] . ', \'' . $M['browser']->displayRating($rating) . '\', \'<a href="steam://connect/' . $info[0] . ':' . $info[1] . '"><span class="glyphicon glyphicon-play custom-connect"></span></a> <span id="favoriteItem-' . $i . '">' . $toFav . '</span>\', \'' . addslashes($serverInfo['GameTags']) . '\', \'' . $table . '\');</script>';
			}
			else
			{
				echo '0';
			}
			
			if ($squery)
			{
				$squery->Disconnect();
			}
		}
		
		//echo $M['browser']->json_readable_encode($printArray);
	}
?>