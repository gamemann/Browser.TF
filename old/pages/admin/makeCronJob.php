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
	$message = '';
	
	/* Cron Job Options. */
	$name = 'mastertodatabase';
	
	$cron = 'masterToDatabase';
	
	$display = 'Master Server -> DataBase';
	
	$description = 'Inserts servers from the Master Server list to the database.';
	
	$author = 'Christian Deacon';
	
	$timeRan = 40000;	/* In seconds. */
	
	$options = Array
	(
		'appid' => 0
	);
	
	/* Check if the user has access. */
	if ($userInfo && $userInfo['group'] == 3)
	{
		/* Check if the name exist already. */
		
		/* Prepare the query. */
		$db->where('name', $name);
		
		/* Execute the query. */
		$db->get('cronjobs');
		
		if ($db->count < 1)
		{
			/* Insert the cron job. */
			
			/* Prepare the query. */
			$data = Array
			(
				'name' => $name,
				'cron' => $cron,
				'display' => $display,
				'description' => $description,
				'author' => $author,
				'options' => json_encode($options, JSON_PRETTY_PRINT),
				'ranevery' => $timeRan,
				'timeadded' => time(),
				'enabled' => 1
			);
			
			/* Execute the query. */
			$check = $db->insert('cronjobs', $data);
			
			/* Check the query. */
			if ($check)
			{
				$message = 'Successfully added cron job (' . $name . ')!';
			}
			else
			{
				$message = 'An error has occurred: ' . $db->getLastError();
			}
		}
		else
		{
			$message = 'A cron job with the same name already exist.';
		}
	}
	else
	{
		$message = 'You do not have permission to this page.';
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
		
		<title>Make Cron Job · Browser.TF</title>
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
					<!-- Main Content (100%) -->
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<!-- Block: Server Browser -->
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<h3 class="blockTitle">Server Browser</h3>
							<div class="blockContent">
								<p class="text-center"><?php echo $message; ?></p>
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