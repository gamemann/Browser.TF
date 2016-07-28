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
	
	// APPID
	$appID = $M['browser']->getGameID();
	
	// Now, we can set up the page!
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
		
		<title>Favorites · Browser.TF</title>
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
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<table id="favoritestable">
							<thead>
								<tr>
									<th>Host Name</th>
									<th>Players</th>
									<th>Maximum Players</th>
									<th>Map</th>
									<th>IP</th>
									<th>Port</th>
									<th>Rating</th>
									<th>Options</th>
									<th>Tags</th>
								</tr>
							</thead>
							<tbody id="table-tbody">
							<?php
								/* Prepare the query. */
								$db->where('aid', $userInfo['id']);
								$db->where('appid', $appID);
								
								/* Execute the query. */
								$favorites = $db->get('favorites');
								
								/* Fetch the rows. */
								foreach ($favorites as $favorite)
								{
									/* Receive the server information. */
									
									/* Prepare the query. */
									$db->where('ip', $favorite['ip']);
									$db->where('port', $favorite['port']);
									
									/* Execute the query. */
									$server = $db->get('`' . $appID . '-servers`');
									
									/* Fetch the rows. */
									foreach ($server as $row)
									{
										/* Receive the information. */
										$M['browser']->printServerToTable($userInfo, $row);
									}
								}
							?>
							</tbody>
						</table>
						
						<h3>Filters</h3>
						<table id="filtertable" border="0">
							<!-- Empty -->
							<tr>
								<td><input type="checkbox" id="hideserversempty" value="1" /></td>
								<td>Hide Empty Servers</td>
							</tr>				
							
							<!-- Full -->
							<tr>
								<td><input type="checkbox" id="hideserversfull" value="1" /></td>
								<td>Hide Full Servers</td>
							</tr>
							
							<!-- > 24 Max Players -->
							<tr>
								<td><input type="checkbox" id="high24" value="1" /></td>
								<td>> 24 Max Players</td>
							</tr>
							
							<!-- < 24 Max Players -->
							<tr>
								<td><input type="checkbox" id="low24" value="1" /></td>
								<td>< 24 Max Players</td>
							</tr>
						</table>
					</div>
				</div>
				<script>reInitTable("#favoritestable");</script>
			</div>
			
			<?php
				$M['main']->loadFooter();
			?>
		</div>
		
		<div id="temp">
		</div>
	</body>
</html>