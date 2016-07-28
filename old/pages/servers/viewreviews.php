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
	
	$ip = 0;
	
	if (isset($_GET['ip']))
	{
		$ip = $_GET['ip'];
	}	
	
	$port = 0;
	
	if (isset($_GET['port']))
	{
		$port = $_GET['port'];
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
		
		<title>Viewing Reviews · Browser.TF</title>
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
					<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<?php
								echo '<h3 class="blockTitle">Reviews For ' . $ip . ':' . $port . '</h3>';
								
								echo '<div class="blockContent">';
									/* Prepare the query. */
									$db->where('ip', $ip);
									$db->where('port', $port);
									
									/* Execute the query. */
									$reviews = $db->get('ratings');
									
									if ($db->count > 0)
									{
										foreach ($reviews as $row)
										{
											$uInfo = $M['users']->getUserInfo($row['aid']);
											
											$rating = $row['rating'] * 10;
											
											echo '<div class="review" id="review-' . $row['id'] . '">';
												echo '<div class="row">';
													echo '<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 review-info">';
														echo '<img src="' . $uInfo['avatarmedium'] . '" />';
														echo '<br />';
														echo '<span class="review-username">' . $M['users']->formatUser($row['aid']) . '</span>';
														echo '<br />';
														echo '<span class="review-rating">' . $M['browser']->displayRating($rating) . '/100</span>';
													echo '</div>';	
													

													echo '<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 review-content">';
														echo $M['bbcodes']->parseText(nl2br(htmlentities($row['review'])));
													echo '</div>';
												echo '</div>';
											echo '</div>';
										}
									}
									else
									{
										echo '<p class="text-center">No reviews for this server.</p>';
									}
								echo '</div>';
							?>
						</div>
					</div>
					
					<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
					
					</div>
				</div>
			</div>
			
			<?php
				$M['main']->loadFooter();
			?>
		</div>
	</body>
</html>