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
		
		<title>About · Browser.TF</title>
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
					<!-- Main Content (66%) -->
					<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
						<!-- Block: Server Browser -->
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<h3 class="blockTitle">Server Browser</h3>
							<div class="blockContent">
								<p>The default server browser is used for finding any type of community server. We have filtered out all Valve official servers which appear on the current in-game server browser. Servers are sorted by rating and player count. If you link your Steam account, you can rate servers and write reviews.</p>
							</div>
						</div>		
						
						<!-- Block: Stock Server Browser -->
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<h3 class="blockTitle">Stock Browser</h3>
							<div class="blockContent">
								<p>If it's your first time visiting, you most likely are asking yourself: "What is the stock server browser for?". Well, it is generally difficult to find servers with:</p>
								
								<ul>
									<li><span class="server-item-name">No MOTD Ads</span></li>
									<li><span class="server-item-name">No Pay-To-Win Advantages</span></li>
									<li><span class="server-item-name">No Lag</span></li>
									<li><span class="server-item-name">Stock Gameplay</span></li>
								</ul>
								
								<p>This is due to the fact that:</p>
								
								<ol>
									<li>There are many custom servers out there.</li>
									<li>There are many bad community servers out there (e.g. Pay-To-Win, etc).</li>
								</ol>
								
								<p>With that being said, players may think the TF2 quick-play system would populate these great community servers. However, that is incorrect, Valve has made their official servers the main priority resulting in community servers struggling for population. Therefore, we've decided to make a Stock Browser that will list all of the great TF2 community servers with stock game-play that deserve population.</p>
							</div>
						</div>
					</div>
					
					<!-- SideBar (33%) -->
					<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
						<!-- Block: Who Are We? -->
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<h3 class="blockTitle">Who Are We?</h3>
							<p class="blockContent">Browser.TF is a web-side server browser made for Team Fortress 2. The idea of Browser.TF was brought up by <?php echo $M['users']->formatUser(2); ?>. From there, <?php echo $M['users']->formatUser(7); ?> built the website from scratch.</p>
						</div>		
						
						<!-- Block: F.A.Q. -->
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<h3 class="blockTitle">F.A.Q</h3>
							<div class="blockContent">
								<ul style="list-style-type: none;">
									<!-- How Do I Claim My Server -->
									<li>Q: <span class="server-item-name">How do I claim my server?</span></li>
									<li>A: First, you must link your Steam account. From there, go to your server's main page (browser.tf/pages/servers/vieserver.php?ip=IP&port=Port). Make sure your server's host name is set to "Browser.TF" (case-sensitive and without the quotes). Hit the claim server button under "Server Options". Done!</span></li>
									<br />
									<br />
									<li>Q: <span class="server-item-name">Do we have a Steam group?</span></li>
									<li>A: Yes, we do! You can view the public Steam group <a href="http://steamcommunity.com/groups/browsertf">here</a>!</span></li>
								</ul>
							</div>
						</div>						
						
						<!-- Block: Suggestions -->
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<h3 class="blockTitle">Suggestions</h3>
							<div class="blockContent">
								<p>If you have a suggestion, please E-Mail or add <?php echo $M['users']->formatUser(7); ?> on Steam. <br /><br />Roy's E-Mail list:</p>
								<ul>
									<li><a href="mailto:gamemann@gflclan.com">gamemann@gflclan.com</a></li>
									<li><a href="mailto:christiand@thedevelopingcommunity.com">christiand@thedevelopingcommunity.com</a></li>
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