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
		
		<title>Adding Server · Browser.TF</title>
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
				<?php
					$rules = array
					(
						'No MOTD Video Ads' => 'Most players do not like MOTD video ads on servers they play on.',
						'No Pay-To-Win Advantages' => 'It isn\'t fair if players can pay to have advantages towards players.',
						'No Lag' => 'Players deserve a lag-free experience. We will be checking server performance before adding the server.',
						'Stock Gameplay' => 'You must run a stock server (gameplay). SourceMod, MetaMod, etc is allowed, but no custom maps. Instant/Fast respawn is allowed, but you must include it in your host name.'
					);
				?>
				
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-sm-offset-3 col-md-offset-3 col-lg-offset-3">
						<h3 class="blockTitle">Stock Browser Rules</h3>
						<div class="blockContent">
							<ul class="server-rules">
								<?php
									foreach ($rules as $key => $value)
									{
										echo '<li class="rules-item"><span class="server-item-name">' . $key . '</span> - ' . $value . '</li>';
									}
								?>
							</ul>
							<p class="text-center" style="color: #D42020;">Your server will be banned from this list if you break the rules.</p>
						</div>
					</div>
					
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-sm-offset-3 col-md-offset-3 col-lg-offset-3">
						<h3 class="blockTitle">Add Stock Server</h3>
						<div class="blockContent">
							<?php
								if ($userInfo)
								{
									if ($success)
									{
										echo '<p class="text-center alert-message">' . $message . '</p>';
									}
									else
									{
										if (!empty($message))
										{
											echo '<p class="text-center">' . $message . '</p>';
										}
										
										echo '<form action="content/addserver.php" method="POST">';
											echo '<div class="form-group">';
												echo '<label for="ip">Server IP</label>';
												echo '<input type="text" name="ip" id="ip" class="form-control custom-control" />'; 
											echo '</div>';
											
											echo '<div class="form-group">';
												echo '<label for="port">Server Port</label>';
												echo '<input type="text" name="port" id="port" class="form-control custom-control" />';
											echo '</div>';
											
											echo '<div class="form-group">';
												echo '<label for="location">Server Location</label>';
												echo '<input type="text" name="location" id="location" class="form-control custom-control" placeholder="US East..." />';
											echo '</div>';
											
											echo '<div class="form-group">';
												echo '<label for="email">Contact E-Mail</label>';
												echo '<input type="text" name="email" id="email" class="form-control custom-control" placeholder="something@something.com" />';
											echo '</div>';
											
											echo '<div class="form-group">';
												echo '<label for="description">How Did You Find Us?</label>';
												echo '<textarea class="form-control custom-control" name="description" id="description" rows="3"></textarea>';
											echo '</div>';
											
											echo '<div class="text-center"><button type="button" name="addserver" class="btn btn-success" onClick="addServer(); return false;">Add Server!</button></div>';
										echo '</form>';
									}
								}
								else
								{
									echo '<p class="text-center">You must link your Steam account to add servers.</p>';
								}
							?>
						</div>
					</div>
				</div>
				
			</div>
			
			<?php
				$M['main']->loadFooter();
			?>
		</div>
	</body>
</html>