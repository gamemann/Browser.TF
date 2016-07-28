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
		<script src="java/custom_serverbrowser-1.4.js"></script>
		
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
				<li class="active"><a href="index.php">Home <span class="sr-only">(current)</span></a></li>
				<li class="dropdown">
				  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Affiliates <span class="caret"></span></a>
				  <ul class="dropdown-menu">
					<li><a href="https://GFLClan.com/" target="_new">Games For Life</a></li>
					<li><a href="http://steamcommunity.com/groups/gosuas" target="_new">Shut Up And Surf</a></li>
				  </ul>
				</li>
				<li><a href="about.php">About</a></li>
			  </ul>
			</div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
		</nav>
		
		<div class="container-fluid" id="content">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="browser">
					<h1 class="text-center">Welcome To Browser.TF!</h1>
					
					<div id="serverbrowser-placeholder">
						<table id="serverbrowser" class="display" width="100%"></table>
					</div>
					<div id="serverbrowser-filters">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
								<!-- Map -->
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
									<fieldset class="form-group">
										<label for="filter-map">Map</label>
										
										<input type="text" id="filter-map" placeholder="ctf_2fort" /> 
									</fieldset>
								</div>
								
								<!-- Region -->
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
									<fieldset class="form-group form-inline">
										<label for="filter-region">Region</label>
										
										<select id="filter-region" class="form-control"> 
											<option value="-1" selected="selected">Any</option>
											<option value="255">All</option>
											<option value="0">US - East</option>
											<option value="1">US - West</option>
											<option value="2">South America</option>
											<option value="3">Europe</option>
											<option value="4">Asia</option>
											<option value="5">Australia</option>
											<option value="6">Middle East</option>
											<option value="7">Africa</option>
										</select>
									</fieldset>
								</div>								
								
								<!-- Hide Empty Servers -->
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
									<fieldset class="form-group">
										<label for="filter-hideempty"><input type="checkbox" id="filter-hideempty" /> Hide Empty Servers</label>
									</fieldset>
								</div>							

								<!-- Hide Full Servers -->
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
									<fieldset class="form-group">
										<label for="filter-hidefull"><input type="checkbox" id="filter-hidefull" /> Hide Full Servers</label>
									</fieldset>
								</div>									
								
								<!-- > 24 players -->
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
									<fieldset class="form-group">
										<label for="filter-high24"><input type="checkbox" id="filter-high24" /> > 24 MaxPlayers</label>
									</fieldset>
								</div>							

								<!--  24 >= players -->
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
									<fieldset class="form-group">
										<label for="filter-low24"><input type="checkbox" id="filter-low24" />  24 >= MaxPlayers</label>
									</fieldset>
								</div>									
								
								<!-- Anti-Cheat -->
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
									<fieldset class="form-group form-inline">
										<label for="filter-secure">Anti-Cheat</label>
										
										<select id="filter-secure" class="form-control"> 
											<option value="-1" selected="selected">Any</option>
											<option value="1">Secure</option>
											<option value="0">Insecure</option>
										</select>
									</fieldset>
								</div>
							</div>
						</div>
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