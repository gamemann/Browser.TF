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


	$query = $db->query("SELECT * FROM `manualinfo` WHERE `online`=1");

	if ($query)
	{
		while ($row = $query->fetch_assoc())
		{
			if ($row['maxplayers'] > 0 && !empty($row['map']) && !stristr($row['hostname'], 'Valve'))
			{
				if ($userInfo)
				{
					if ($browser->isFavorited($userInfo, $row['ip'], $row['port']))
					{
						$toFav = '<span class="glyphicon glyphicon-star custom-unfav-star" title="Un-Favorite" onclick="unFavoriteServer(\'' . $row['ip'] . '\', ' . $row['port'] . ', 1, ' . $row['id'] . ');"></span>';
					}
					else
					{
						$toFav = '<span class="glyphicon glyphicon-star custom-fav-star" title="Favorite" onclick="favoriteServer(\'' . $row['ip'] . '\', ' . $row['port'] . ', 1, ' . $row['id'] . ');"></span>';
					}
				}
				else
				{
					$toFav = '';
				}
				
				// Add Server Row.
				$rating = $browser->calculateRating($row['ip'], $row['port'], array('population'), true);
				
				echo '<tr>';
					echo '<td></td>';
					echo '<td><a href="#" onClick="switchContent(\'viewserver.php?ip=' . $row['ip'] . ':' . $row['port'] . '\');return false;">' . $row['hostname'] . '</a></td>';
					echo '<td>' . $row['players'] . '</td>';
					echo '<td>' . $row['maxplayers'] . '</td>';
					echo '<td>' . $row['map'] . '</td>';
					echo '<td>' . $row['ip'] . '</td>';
					echo '<td>' . $row['port'] . '</td>';
					echo '<td>' . $browser->displayRating($rating) . '</td>';
					echo '<td><a href="steam://connect/' . $row['ip'] . ':' . $row['port'] . '"><span class="glyphicon glyphicon-play custom-connect"></span></a> <span id="favoriteItem-' . $row['id'] . '">' . $toFav . '</span></td>';
					echo '<td>' . $row['tags'] . '</td>';
				echo '</tr>';
			}
		}
	}
?>
