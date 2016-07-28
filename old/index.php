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
	
	/* Execute cron jobs if needed. */
	if (isset($_REQUEST['do']) && $userInfo['group'] == 3)
	{
		$do = $_REQUEST['do'];
		
		/* Check if it's a cron job. */
		if ($do == 'cronjob')
		{
			/* Get the cron job it wants to execute. */
			$cron = isset($_REQUEST['cron']) ? $_REQUEST['cron'] : false;
			
			/* Prepare the query. */
			$db->where('name', $cron);
			
			/* Execute the query. */
			$cronSelected = $db->getOne('cronjobs');
			
			/* Check the query. */
			if ($cronSelected)
			{
				/* Decode the options. */
				$options = json_decode($cronSelected['options'], true);
				
				/* Execute the cron job. */
				$M['crons']->executeCronJob($cronSelected['cron'], $options);
				
				/* Log a message. */
				$M['main']->logMessage('crons', 'manualExecute', $userInfo['personaname'] . '(' . $userInfo['id'] . ') has manually executed the ' . $cron . ' cron job');
			}
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
		
		<title>Home · Browser.TF</title>
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
						<!-- Announcements -->
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<h3 class="blockTitle">Announcements</h3>
							
							<div class="blockContent">
								<div id="announcements">
									<?php
										/* Prepare the query. */
										$db->orderBy('dateadded', 'desc');
										
										/* Execute the query. */
										$announcements = $db->get('announcements');
										
										/* Check the row count. */
										if ($db->count > 0)
										{
											/* Fetch the rows. */
											foreach ($announcements as $row)
											{
												echo '<div class="announcement" id="announcement_' . $row['id'] . '">';
													echo '<div class="row">';
														// Image...
														echo '<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 a-image-div">';
															$image = 'default.png';
															$path = $row['id'] . '.' . $row['ext'];
															
															if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/images/announcements/' . $path))
															{
																$image = $path;
															}
															
															echo '<img src="/images/announcements/' . $image . '" class="a-image" />';
														echo '</div>';
														
														// Title, etc.
														echo '<div class="col-xs-11 col-sm-11 col-md-11 col-lg-11 a-info">';
															echo '<a href="/pages/announcements/viewannouncement.php?id=' . $row['id'] . '"><span class="a-title">' . $row['title'] . '</span></a>';
															echo '<br />';
															
															$date = date('n-j-y g:i A T', $row['dateadded']);
															
															echo '<span class="a-user">By <span class="a-user-color">' . $M['users']->formatUser($row['uid']) . '</span> on ' . $date . '</span>';
														echo '</div>';
													echo '</div>';
												echo '</div>';
											}
										}
										else
										{
											echo '<p class="text-center">No announcements found...</p>';
										}
									?>
								</div>
							</div>
						</div>
					</div>
					
					<!-- SideBar -->
					<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
						<!-- 5 Recent Logged On Members -->
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<h3 class="blockTitle">Last Five Members To Log In</h3>
							
							<div class="blockContent">
								<?php
									$query = $db->query("SELECT * FROM `accounts` ORDER BY `lastlogin` DESC LIMIT 5");
									
									if ($query)
									{
										if ($query->num_rows > 0)
										{
											while ($row = $query->fetch_assoc())
											{
												$date = date('n-j-y g:i A T', $row['lastlogin']);
												
												echo '<ul class="list-unstyled">';
													echo '<li>' . $M['users']->formatUser($row['id']) . ' - ' . $date . '</li>';
												echo '</ul>';
											}
										}
									}
									
									/* Prepare the query. */
									$db->orderBy('lastlogin', 'desc');
									
									/* Execute the query. */
									$users = $db->get('accounts', 5);
									
									/* Check the row count. */
									if ($db->count > 0)
									{
										/* Fetch the rows. */
										foreach ($users as $row)
										{
											$date = date('n-j-y g:i A T', $row['lastlogin']);
											
											echo '<ul class="list-unstyled">';
												echo '<li>' . $M['users']->formatUser($row['id']) . ' - ' . $date . '</li>';
											echo '</ul>';
										}
									}
								?>
							</div>
						</div>						
						
						<!-- Partners -->
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<h3 class="blockTitle">Partners</h3>
							
							<div class="blockContent">
								<ul>
									<li><a href="http://GFLClan.com/" target="_new">GFL - Games For Life</a></li>
									<li><a href="http://TheDevelopingCommunity.com/" target="_new">TDC - The Developing Community</a></li>
								</ul>
							</div>
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