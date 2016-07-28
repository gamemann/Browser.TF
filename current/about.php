<html>
	<head>
		<title>Basic TF2 Browser</title>
		
		<link rel="stylesheet" type="text/css" href="datatables/datatables.min.css" />
		
		<!-- Custom -->
		<link rel="stylesheet" type="text/css" href="styles/custom_plain-1.3.css" />
		
		<script src="datatables/datatables.min.js"></script>
		<script src="java/pace.js"></script>
		
		<!-- Custom -->
		<script src="java/custom_rotationbackground.js"></script>
		
		<!-- BootStrap stuff -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	
	<body>
		<div id="background"></div>
		<div id="background-layer"></div>
		
		<nav class="navbar navbar-inverse" id="navbar">
		  <div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
			  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			  </button>
			  <a class="navbar-brand" id="custom-navbar-brand" href="index.php">Browser.TF</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			  <ul class="nav navbar-nav">
				<li><a href="index.php">Home</a></li>
				<li class="dropdown">
				  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Affiliates <span class="caret"></span></a>
				  <ul class="dropdown-menu">
					<li><a href="https://GFLClan.com/" target="_new">Games For Life</a></li>
					<li><a href="http://steamcommunity.com/groups/gosuas" target="_new">Shut Up And Surf</a></li>
				  </ul>
				</li>
				<li class="active"><a href="about.php">About <span class="sr-only">(current)</span></a></li>
			  </ul>
			</div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
		</nav>
		
		<div class="container-fluid" id="content">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="browser">
					<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
						<h1 class="text-center">About Browser.TF</h1>
						<p><span class="special">Browser.TF</span> is a web-sided server browser for TF2! Browser.TF was founded on November 21st, 2015 by <a href="http://steamcommunity.com/id/halladay" target="_new">Roy</a> (<span class="special">Christian Deacon</span>). Originally, the website supported many features such as users being able to favorite any server, server owners being able to claim their own server(s), and even at one point, supported Garry's Mod and CS:GO servers. However, after our web server was rebuilt from scratch, Roy didn't have the time to restore the old website. Roy still felt the website's performance wasn't meeting a regular TF2 player's expectation. Therefore, he decided to rebuild Browser.TF from scratch. This time, he wanted to aim for <span class="special">simplicity</span>. That said, the new website can easily load 7000+ servers in <span class="special">2 - 4 seconds</span>. That is very <span class="special">fast</span>! This rebuild wouldn't be possible if <a href="http://steamcommunity.com/id/DoctorMcKay/" target="_new">Dr. McKay</a> didn't show Roy a new way to fetch servers from the Master Server.</p>
						
						<p>Overall, Roy will continue to improve the website by increasing performance and adding extra features. Eventually, support for other games will be added! If you find a bug with the website, please report it to Roy!</p>
					</div>
					
					<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
						<h1 class="text-center">Credits</h1>
						<ul>
							<li><a href="http://steamcommunity.com/id/halladay/" target="_new">Roy</a> (Christian Deacon) - Fully Building the back-end of both websites (original and new).</li>
							<li><a href="http://steamcommunity.com/id/DoctorMcKay/" target="_new">Dr. McKay</a> - Helping out by showing Roy a new way to retrieve servers from the Master Server.</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
		<div class="container" id="footer">
			<p class="text-center"><span class="special">Simple</span> and <span class="special">Fast</span></p>
			<p class="text-center">By <a href="http://steamcommunity.com/id/halladay/" target="_new">Roy</a> (Christian Deacon)</p>
		</div>
	</body>
</html>