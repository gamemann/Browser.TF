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
	$id = 0;
	
	if (isset($_GET['id']))
	{
		$id = $_GET['id'];
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
		
		<title>Viewing Announcement · Browser.TF</title>
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
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<!-- Announcements -->
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<?php
								/* Prepare the query. */
								$db->where('id', $id);
								
								/* Execute the query. */
								$row = $db->getOne('announcements');
								
								if ($row)
								{
									$title = $row['title'];
									$description = $row['description'];
									$content = $row['content'];
									$content = $M['bbcodes']->parseText(nl2br(htmlentities($content)));
									$date = date('n-j-y g:i A T', $row['dateadded']);
									
									echo '<h3 class="blockTitle">' . $title . '</h3>';
									
									echo '<div class="blockContent">';
										echo $content;
										echo '<br /><br /><span class="a-user">By ' . $M['users']->formatUser($row['uid']) . ' at ' . $date;
									echo '</div>';
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