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
		
		<title>Admin · Browser.TF</title>
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
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<h3 class="blockTitle">Servers</h3>
						<div class="blockContent">
							<?php
								if ($M['users']->hasGroupAccess($userInfo, 'AP-Servers'))
								{
									/* Prepare the query. */
									
									/* Execute the query. */
									$appServers = $db->get('approved_servers');
									
									if ($db->count > 0)
									{
										echo '<table id="serversadmin" class="blockTable">';
											echo '<thead>';
												echo '<tr>';
													echo '<th>ID</th>';
													echo '<th>Server IP</th>';
													echo '<th>Server Port</th>';
													echo '<th>Action</th>';
													echo '<th>Connect</th>';
												echo '</tr>';
											echo '</thead>';
											
											echo '<tbody>';
												foreach ($appServers as $row)
												{
													$class = '';
													if ($row['enabled'])
													{
														$action = '<span class="glyphicon glyphicon-remove-circle custom-disable" style="cursor: pointer;" title="Disable" onclick="disableServer(' . $row['id'] . ', \'' . $row['ip' ] .'\', ' . $row['port'] . ');"></span>';
													}
													else
													{
														$action = '<span class="glyphicon glyphicon-ok-circle custom-enable" style="cursor: pointer;" title="Enable" onclick="enableServer(' . $row['id'] . ', \'' . $row['ip' ] .'\', ' . $row['port'] . ');"></span>';
														$class = 'server-disabled';
													}
													
													echo '<tr id="' . $row['id'] . '-row" class="' . $class . '">';
														echo '<td>' . $row['id'] . '</td>';
														echo '<td>' . $row['ip'] . '</td>';
														echo '<td>' . $row['port'] . '</td>';
														echo '<td id="' . $row['id'] . '-action">' . $action . '</td>';
														echo '<td><a href="steam://connect/' . $row['ip'] . ':' . $row['port'] . '"><span class="glyphicon glyphicon-play custom-connect"></span></a></td>';
													echo '</tr>';
												}
											echo '</tbody>';
										echo '</table>';
									}
								}
								else
								{
									echo '<p class="text-center">No Access.</p>';
								}
							?>
						</div>
					</div>

					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<h3 class="blockTitle">Server Requests</h3>
						<div class="blockContent">
							<?php
								if ($M['users']->hasGroupAccess($userInfo, 'AP-ServerRequests'))
								{
									/* Prepare the query. */
									
									/* Execute the query. */
									$requests = $db->get('requests');
									
									if ($db->count > 0)
									{
										echo '<table id="requestsadmin" class="blockTable">';
											echo '<thead>';
												echo '<tr>';
													echo '<th>ID</th>';
													echo '<th>Account ID</th>';
													echo '<th>Server IP</th>';
													echo '<th>Server Port</th>';
													echo '<th>Server Location</th>';
													echo '<th>Contact Email</th>';
													echo '<th>Status</th>';
													echo '<th>Date Requested</th>';
													echo '<th>Notes</th>';
													echo '<th>Actions</th>';
													echo '<th>Connect</th>';
												echo '</tr>';
											echo '</thead>';
											
											echo '<tbody>';
												foreach($requests as $row)
												{
													$class = '';
													$actions = '';
													if ($row['status'] == 1)
													{
														$class = 'status-approved';
														$actions = '---';
													}
													elseif ($row['status'] == 2)
													{
														$class = 'status-unapproved';
														$actions = '---';
													}
													else
													{
														$actions = '<span class="glyphicon glyphicon-ok-circle custom-enable" style="cursor: pointer;" title="Accept" onclick="addRequest(' . $row['id'] . ', \'' . $row['ip'] . '\', \'' . $row['port'] . '\');"></span> <span class="glyphicon glyphicon-remove-circle custom-disable" style="cursor: pointer;" title="Deny" onclick="removeRequest(' . $row['id' ] .', \'' . $row['ip'] . '\', \'' . $row['port'] . '\');"></span>';
													}
													
													$date = date('n-j-y', $row['timeadded']);
													
													if (!empty($row['notes']))
													{
														$notes = $row['notes'];
													}
													else
													{
														$notes = '<textarea id="' . $row['id'] . '-notes"></textarea>';
													}
													
													echo '<tr id="' . $row['id'] . '-request-row" class="' . $class . '">';
														echo '<td>' . $row['id'] . '</td>';
														echo '<td>' . $row['aid'] . '</td>';
														echo '<td>' . $row['ip'] . '</td>';
														echo '<td>' . $row['port'] . '</td>';
														echo '<td>' . $row['location'] . '</td>';
														echo '<td>' . $row['email'] . '</td>';
														echo '<td>' . $row['status'] . '</td>';
														echo '<td>' . $date . '</td>';
														echo '<td id="' . $row['id'] . '-request-notesrow">' . $notes . '</td>';
														echo '<td id="' . $row['id'] . '-request-action">' . $actions . '</td>';
														echo '<td><a href="steam://connect/' . $row['ip'] . ':' . $row['port'] . '"><span class="glyphicon glyphicon-play custom-connect"></span></a></td>';
													echo '</tr>';
												}
											echo '</tbody>';
										echo '</table>';
										// Quick filters.
										echo '<table id="filtertable" border="0">';
											echo '<tr>';
												echo '<td>';
													echo '<select id="filter-request">';
														echo '<option value="-1">All</option>';
														echo '<option value="0">Not Checked</option>';
														echo '<option value="1">Accepted</option>';
														echo '<option value="2">Denied</option>';
													echo '</select>';
												echo '</td>';
											echo '</tr>';
										echo '</table>';
									}
								}
								else
								{
									echo '<p class="text-center">No Access.</p>';
								}
							?>
						</div>
					</div>
					
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<h3 class="blockTitle">Server Reports</h3>
						<div class="blockContent">
							<?php
								if ($M['users']->hasGroupAccess($userInfo, 'AP-ServerReports'))
								{
									/* Prepare the query. */
									
									/* Execute the query. */
									$reports = $db->get('reports');
									
									if ($db->count > 0)
									{
										echo '<table id="reportsadmin" class="blockTable">';
											echo '<thead>';
												echo '<tr>';
													echo '<th>ID</th>';
													echo '<th>Account ID</th>';
													echo '<th>Server IP</th>';
													echo '<th>Server Port</th>';
													echo '<th>Date Reported</th>';
													echo '<th>Report</th>';
												echo '</tr>';
											echo '</thead>';
											
											echo '<tbody>';
												foreach ($reports as $row)
												{
													$date = date('n-j-y', $row['dateadded']);

													echo '<tr id="' . $row['id'] . '-report-row">';
														echo '<td>' . $row['id'] . '</td>';
														echo '<td>' . $row['aid'] . '</td>';
														echo '<td>' . $row['ip'] . '</td>';
														echo '<td>' . $row['port'] . '</td>';
														echo '<td>' . $date . '</td>';
														echo '<td>' . $row['reason'] . '</td>';
													echo '</tr>';
												}
											echo '</tbody>';
										echo '</table>';
									}
								}
								else
								{
									echo '<p class="text-center">No Access.</p>';
								}
							?>
						</div>
					</div>	
					
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<h3 class="blockTitle">Users</h3>
						<div class="blockContent">
							<?php
								if ($M['users']->hasGroupAccess($userInfo, 'AP-Users'))
								{
									/* Prepare the query. */
									
									/* Execute the query. */
									$accounts = $db->get('accounts');
									
									if ($db->count > 0)
									{
										echo '<table id="usersadmin" class="blockTable">';
											echo '<thead>';
												echo '<tr>';
													echo '<th>ID</th>';
													echo '<th>Steam ID</th>';
													echo '<th>Avatar</th>';
													echo '<th>Steam Name</th>';
													echo '<th>Last Login</th>';
													echo '<th>Actions</th>';
												echo '</tr>';
											echo '</thead>';
											
											echo '<tbody>';
												foreach($accounts as $row)
												{
													$actions = '---';
													
													if ($userInfo['group'] == 3)
													{
														// Root, gets access to promote/demote members.
														if ($row['group'] == 1)
														{
															// Member
															$actions = '<span id="' . $row['id'] . '-users-action"><span class="glyphicon glyphicon-star custom-fav-star-users" title="Promote" onclick="promoteUser(' . $row['id'] . ');"></span></span>';
														}
														elseif ($row['group'] == 2)
														{
															// Admin
															$actions = '<span id="' . $row['id'] . '-users-action"><span class="glyphicon glyphicon-star custom-unfav-star-users" title="Demote" onclick="demoteUser(' . $row['id'] . ');"></span></span>';
														}
														
														// Add the demote user button.
														if ($row['group'] != 3)
														{
															$actions .= ' <span class="glyphicon glyphicon-remove-circle custom-disable" style="cursor: pointer;" title="Disable" onclick="disableUser(' . $row['id'] . ');"></span>';
														}
													}
													$steam = json_decode($row['steaminfo'], true);
													
													$lastlogin = date('n-j-y g:i A', $row['lastlogin']);

													echo '<tr id="' . $row['id'] . '-user-row">';
														echo '<td>' . $row['id'] . '</td>';
														echo '<td><a href="http://steamid.co/player.php?input=' . $row['steamid'] . '" target="_blank">' . $row['steamid'] . '</a></td>';
														echo '<td><img src="' . $steam['avatarmedium'] . '" alt="avatar" /></td>';
														echo '<td>' . $M['users']->formatUser($row['id']) . '</td>';
														echo '<td>' . $lastlogin . '</td>';
														echo '<td>' . $actions . '</td>';
													echo '</tr>';
												}
											echo '</tbody>';
										echo '</table>';
									}
								}
								else
								{
									echo '<p class="text-center">No Access.</p>';
								}
							?>
						</div>
					</div>				

					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<h3 class="blockTitle">Announcements</h3>
						<div class="blockContent">
							<?php
								if ($M['users']->hasGroupAccess($userInfo, 'AP-Announcements'))
								{
									echo '<form action="" method="POST">';
										echo '<div class="form-group">';
											echo '<label for="announcement-title">Title</label>';
											echo '<input type="text" name="announcement-title" id="announcement-title" class="form-control custom-control" />'; 
										echo '</div>';
										
										echo '<div class="form-group">';
											echo '<label for="announcement-description">Description</label>';
											echo '<textarea class="form-control custom-control" name="announcement-description" id="announcement-description" rows="3"></textarea>';
										echo '</div>';
										
										echo '<div class="form-group">';
											echo '<label for="announcement-content">Content</label>';
											echo '<textarea class="form-control custom-control" name="announcement-content" id="announcement-content" rows="10"></textarea>';
										echo '</div>';
										
										echo '<div class="text-center"><button type="button" name="addserver" class="btn btn-success" onClick="addAnnouncement(); return false;">Add Announcement!</button></div>';
									echo '</form>';
								}
								else
								{
									echo '<p class="text-center">No Access.</p>';
								}
							?>
						</div>
					</div>
					
					<script>
						$('#serversadmin').DataTable({
							"lengthMenu": [ 64, 128, 500, 2000],
							responsive: true
						});				
						
						var requestTable = $('#requestsadmin').DataTable({
							"order": [[0, 'desc']],
							"lengthMenu": [ 64, 128, 500, 2000],
							responsive: true
						});		
						
						var reportTable = $('#reportsadmin').DataTable({
							"order": [[0, 'desc']],
							"lengthMenu": [ 64, 128, 500, 2000],
							responsive: true
						});		
						
						var reportTable = $('#usersadmin').DataTable({
							"order": [[3, 'asc']],
							"lengthMenu": [ 64, 128, 500, 2000],
							responsive: true
						});
						
						$('#requestsadmin').css('width', '100%');
						
						$('#filter-request').change(function()
						{
							requestTable.draw();
						});
					</script>
				</div>
				
			</div>
			
			<?php
				$M['main']->loadFooter();
			?>
		</div>
	</body>
</html>