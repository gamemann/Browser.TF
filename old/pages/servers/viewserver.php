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
	
	// Steam Condenser.
	require_once($_SERVER['DOCUMENT_ROOT'] . '/condenser/steam-condenser.php');
	
	// User's information.
	$userInfo = $M['users']->getUserInfo();
	
	// Now, we can set up the page!
	error_reporting(E_ERROR);
	
	$ip = 0;
	$port = 0;

	if (isset($_GET['ip']))
	{
		$ip = $_GET['ip'];
	}	
	
	if (isset($_GET['port']))
	{
		$port = $_GET['port'];
	}

	if ($ip == 0 || $port == 0)
	{
		die ('Invalid server.');
	}

	// Retrieve all the information
	$online = true;
	$serverInfo = array();
	$playersArray = array();

	if (!empty($ip))
	{
		$squery = new SourceServer($ip, $port);
		
		try
		{
			$squery->initialize();
			$serverInfo = $squery->getServerInfo();
			$playersArray = $squery->getPlayers();
		}
		catch (TimeoutException $e) 
		{
			$online = false;
		}
	}
?>

<html>
	<head>
		<?php
			$M['main']->loadJS();
			$M['main']->loadCSS();
		?>
		
		<!-- BootStrap stuff -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>Viewing Server · Browser.TF</title>
	</head>
	
	<body>
		<div style="display: none;"><?php echo steamlogin(); ?></div>
		<?php
			$M['main']->loadUserBar($userInfo);
			$M['main']->loadLogo();
			$M['main']->loadNavBar(__FILE__, $userInfo);
		?>
		<div class="container-fluid" id="main">
			<!-- Page Specific Content -->
			<div id="page-content">
				<!-- Page Content... -->
				<div class="row">
					<!-- Main Content -->
					<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
						<!-- Server Details -->
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<h3 class="blockTitle">Server Details (<?php echo $ip . ':' . $port; ?>)</h3>
							<div class="blockContent">
								<ul class="server-details">
									<?php
										if (!isset($serverInfo['serverName']))
										{
											echo '<li class="server-item"><span class="alert-message">SERVER OFFLINE</span></li>';
										}
										else
										{
											if ($serverInfo['dedicated'] == 'd') 
											{ 
												$dedicated = 'Yes'; 
											} 
											else 
											{ 
												$dedicated = 'No'; 
											}
											
											
											if ($serverInfo['operatingSystem'] == 'w') 
											{ 
												$OS = 'Windows'; 
											} 
											elseif ($serverInfo['operatingSystem'] == 'l') 
											{ 
												$OS = 'Linux'; 
											}
											else
											{
												$OS = 'Unknown';
											}
											
											if ($serverInfo['secureServer'] == '1') 
											{ 
												$secure = 'Yes'; 
											} 
											else 
											{ 
												$secure = 'No'; 
											}
											
											echo '<li class="server-item"><span class="server-item-name">Host Name</span>: ' . $serverInfo['serverName'] . '</li>';
											echo '<li class="server-item"><span class="server-item-name">Players</span>: ' . $serverInfo['numberOfPlayers'] . '</li>';
											echo '<li class="server-item"><span class="server-item-name">Bots</span>: ' . $serverInfo['botNumber'] . '</li>';
											echo '<li class="server-item"><span class="server-item-name">Maximum Players</span>: ' . $serverInfo['maxPlayers'] . '</li>';
											echo '<li class="server-item"><span class="server-item-name">Map</span>: ' .  $serverInfo['mapName'] . '</li>';
											echo '<li class="server-item"><span class="server-item-name">Dedicated</span>: ' . $dedicated . '</li>';
											echo '<li class="server-item"><span class="server-item-name">Operating System</span>: ' . $OS . '</li>';
											echo '<li class="server-item"><span class="server-item-name">VAC</span>: ' . $secure . '</li>';
											
											// Owner
											$owner = $M['browser']->getServerOwner($ip, $port);
											
											if ($owner > 0)
											{
												$ownerInfo = $M['users']->getUserInfo($owner);
												
												if ($ownerInfo)
												{
													echo '<br />';
													echo '<li class="server-item"><span class="server-item-name">Owner</span>: <img src="' . $ownerInfo['avatar'] . '" alt="avatar" /> ' . $M['users']->formatUser($owner) . '</li>';
												}
											}
										}
									?>
								</ul>
							</div>
						</div>
						
						<!-- Server Players -->
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<h3 class="blockTitle">Server Players</h3>
							<div class="blockContent">
								<table id="playerstable" class="blockTable">
									<thead>
										<tr>
											<th>Name</th>
											<th>Frags</th>
											<th>Time (Minutes)</th>
										</tr>
									</thead>
									<tbody>
										<?php
											// Time to begin :D
											if (is_array($playersArray))
											{
												foreach ($playersArray as $value)
												{
													echo '<tr>';
														echo '<td>' . $value->getName() . '</td>';
														echo '<td>' . $value->getScore() . '</td>';
														echo '<td>' . round($value->getConnectTime() / 60, 0, PHP_ROUND_HALF_UP) . '</td>';
													echo '</tr>';
												}
											}
										?>
									</tbody>
								</table>
								
								<script>
									$('#playerstable').DataTable({
										"lengthMenu": [ 64, 128, 500, 2000],
										"order": [[1, 'desc'], [2, 'desc']],
										responsive: true
									});
								</script>
							</div>
						</div>
					</div>
					
					<!-- SideBar -->
					<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
						<!-- Server Ratings -->
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<h3 class="blockTitle">Server Ratings</h3>
							<div class="blockContent">
								<ul class="server-details">
									<?php
										// First, the overall rating.
										$overall = $M['browser']->calculateRating($ip, $port, array());
									?>
									<li class="server-item"><span class="server-item-name">Overall</span>: <?php echo $M['browser']->displayRating($overall); ?></li>
									<br />
									<?php
										$allRatings = $M['browser']->calculateAllRatings($ip, $port, array());
										
										foreach ($allRatings as $key => $value)
										{
											// Easy Enough 
											echo '<li class="server-item"><span class="server-item-name">' . ucfirst($value['display']) . '</span>: ' . $M['browser']->displayRating(round($value['rating'], 0, PHP_ROUND_HALF_UP)) . '</li>';
										}
									?>
								</ul>
								<p class="text-center"><a href="/pages/servers/viewreviews.php?ip=<?php echo $ip; ?>&port=<?php echo $port; ?>">View All Reviews</a></p>
							</div>
						</div>
						
						<!-- Server Stats -->
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<h3 class="blockTitle">Server Stats (last 30 days)</h3>
							<div class="blockContent">
								<ul class="server-details">
									<?php
										// Let's get the average players in the past month.
										$players = 0;
										$total = 0;
										
										$query = $db->query("SELECT * FROM `population` WHERE `ip`='" . $ip . "' AND `port`=" . $port . " AND `timestamp` > " . 30 * 86400);	// 30 days.
										
										if ($query && $query->num_rows > 0)
										{
											while ($row = $query->fetch_assoc())
											{
												$players += $row['population'];
												$total++;
											}
										}
										
										if ($players > 0 && $total > 0)
										{
											$averagePlayers = round($players / $total, 0, PHP_ROUND_HALF_UP);
										}
										else
										{
											$averagePlayers = 0;
										}
									?>
									<li class="server-item"><span class="server-item-name">Average Players</span>: <?php echo $averagePlayers; ?></li>
									<li class="server-item"><span class="server-item-name">Reviews Count</span>: <?php echo $M['browser']->getReviewsCount($ip, $port); ?></li>
								</ul>
								<p class="text-center">Tracking since 10-7-15</p>
							</div>
						</div>		
						
						<!-- Server Options -->
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<h3 class="blockTitle">Server Options</h3>
							<div class="blockContent">
								<ul class="server-details">
									<?php
										echo '<li class="server-item"><a href="steam://connect/' . $ip . ':' . $port . '"><span class="glyphicon glyphicon-play custom-connect"></span></a> Connect</li>';
										if ($userInfo)
										{
											// Favorite.
											if ($M['browser']->isFavorited($userInfo, $ip, $port))
											{
												$toAdd = '<span class="glyphicon glyphicon-star custom-unfav-star" onclick="unFavoriteServer(\'' . $ip . '\', ' . $port . ', 0, 1);"></span> Un-Favorite';
											}
											else
											{
												$toAdd = '<span class="glyphicon glyphicon-star custom-fav-star" onclick="favoriteServer(\'' . $ip . '\', ' . $port . ', 0, 1);"></span> Favorite';
											}
											
											echo '<li class="server-item" id="favoriteItem-1">' . $toAdd . '</li>';
											
											// Owner
											if (!$M['browser']->isOwner($userInfo, $ip, $port))
											{
												$toAdd = '<span class="glyphicon glyphicon-home custom-fav-star" onclick="claimServer(\'' . $ip . '\', ' . $port . ');"></span> Claim Ownership';
											}
											else
											{
												$toAdd = '<span class="glyphicon glyphicon-home custom-unfav-star"></span> Claimed';
											}
											
											echo '<li class="server-item" id="claimItem">' . $toAdd . '</li>';
										}
										
									?>
								</ul>
							</div>
						</div>		
						
						<!-- Write Review -->
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<h3 class="blockTitle">Write A Review</h3>
							<div class="blockContent">
								<div id="review-block">
									<?php
										if ($userInfo)
										{
											if ($M['browser']->isReviewed($userInfo, $ip, $port))
											{
												echo 'You have already reviewed this server.';
											}
											else
											{
												// Rating
												echo '<div class="form-group">';
													echo '<label for="rating">Rating</label>';
													echo '<select id="rating" class="form-control custom-control">';
														for ($i = 1; $i <= $M['browser']->getMaxRating(); $i++)
														{
															echo '<option value="' . $i . '">' . $i . '</option>';
														}
													echo '</select>';
												echo '</div>';								
												
												echo '<div class="form-group">';
													echo '<label for="review">Review</label>';
													echo '<textarea id="review" class="form-control custom-control" rows="5" placeholder="Talk about the performance, community quality, etc..."></textarea>';
												echo '</div>';
												
												echo '<div class="text-center"><button type="button" onclick="submitReview(\'' . $ip . '\', ' . $port . ');">Submit!</button></div>';
												echo '</div>';
											}
										}
										else
										{
											echo '<div class="text-center">You must link your Steam account to use these features.</div>';
										}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
					$squery->Disconnect();
				?>
			</div>
			
			<?php
				$M['main']->loadFooter();
			?>
		</div>
	</body>
</html>