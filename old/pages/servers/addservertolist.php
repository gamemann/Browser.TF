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
		
		<title>Adding Server To List · Browser.TF</title>
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
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-sm-offset-3 col-md-offset-3 col-lg-offset-3">
						<h3 class="blockTitle">Add Server To List</h3>
						<div class="blockContent">
							<p class="text-center">If a server is missing from the list, please add it through here. You're adding a server using the <?php echo $M['browser']->getGameID(); ?> AppID. Please change the game if that is incorrect.</p>
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
										
										echo '<form method="POST">';
											echo '<div class="form-group">';
												echo '<label for="ip">Server IP</label>';
												echo '<input type="text" name="ip" id="ip" class="form-control custom-control" />'; 
											echo '</div>';
											
											echo '<div class="form-group">';
												echo '<label for="port">Server Port</label>';
												echo '<input type="text" name="port" id="port" class="form-control custom-control" />';
											echo '</div>';
											
											echo '<div class="text-center"><button type="button" name="addserver" class="btn btn-success" onClick="addServerToList(); return false;">Add Server!</button></div>';
										echo '</form>';
									}
								}
								else
								{
									echo '<p class="text-center">You must link your Steam account to add servers to the list.</p>';
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