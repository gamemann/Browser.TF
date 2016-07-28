<?php
	class main
	{
		/* Variables. */
		protected $db;
		protected $gameID;
		
		public function init()
		{
			/* Get the other global modules. */
			global $M;
			$this->M = $M;
			
			/* Receive the database handle. */
			$this->db = $this->M['mysql']->receiveDataBase();
			
			/* Get the Game ID. */
			$this->gameID = $this->M['browser']->getGameID();
		}
		
		// Loads all the JavaScript files.
		public function loadJS()
		{
			/* Include files outside of the JS folder here. */
			echo '<script src="/DataTables/datatables.min.js" type="text/javascript"></script>';
			
			/* Prepare the query. */
			$this->db->where('type', 2);
			$this->db->where("(`appid` = 0 OR `appid` = ?)", Array($this->gameID));
			$this->db->orderBy('listorder', 'asc');
			
			/* Execute the query. */
			$files = $this->db->get('loadfiles');
			
			/* Check the row count. */
			if ($this->db->count > 0)
			{
				/* Fetch the rows. */
				foreach ($files as $file)
				{
					/* Include the JavaScript file. */
					echo '<script src="/js/' . $file['file'] . '" type="text/javascript"></script>';
				}
			}
		}
		
		// Loads all the CSS files.
		public function loadCSS()
		{
			/* Include CSS files outside of the CSS folder here. */
			echo '<link rel="stylesheet" type="text/css" href="/DataTables/datatables.min.css" />';
			
			/* Prepare the query. */
			$this->db->where('type', 1);
			$this->db->where("(`appid` = 0 OR `appid` = ?)", Array($this->gameID));
			$this->db->orderBy('listorder', 'asc');
			
			/* Execute the query. */
			$files = $this->db->get('loadfiles');
			
			/* Check the row count. */
			if ($this->db->count > 0)
			{
				/* Fetch the rows. */
				foreach ($files as $file)
				{
					/* Include the CSS file. */
					echo '<link rel="stylesheet" type="text/css" href="/css/' . $file['file'] . '" />';
				}
			}
		}
		
		// Loads the NavBar.
		public function loadNavBar($currentPage='', $userInfo = 0)
		{
			/* Add the transparency to the page. */
			echo '<div id="transparentmobile" class="visible-xs"></div>';
			
			/* Add the background to the page. */
			echo '<div id="background" class="hidden-xs">';
				echo '<div id="transparent" class="hidden-xs"></div>';
			echo '</div>';
			
			/* Create the NavBar container. */
			echo '<div id="navbar" class="container">';
			
				/* Start the non-Mobile NavBar */
				echo '<ul id="navlist">';
				
					/* Prepare the query. */
					$this->db->where('parent', 0);
					$this->db->where("(`appid` = 0 OR `appid` = ?)", Array($this->gameID));
					$this->db->orderBy('listorder', 'asc');
					
					/* Execute the query. */
					$navbars = $this->db->get('navbar');
					
					/* Check the row count. */
					if ($this->db->count > 0)
					{
						/* Fetch the rows. */
						foreach ($navbars as $row)
						{
							/* Check if the permissions column is not empty. */
							if (!empty($row['permissions']))
							{
								/* If the user does not have permission to view the NavBar, move to the next item. */
								if (!$this->M['users']->hasGroupAccess($userInfo, 'navitem-' . $row['id']))
								{
									continue;
								}
							}
							
							/* Declare default values. */
							$newTab = '';
							$selected = '';
							
							/* If it's a new tab, fill out the $newTab variable. */
							if ($row['newtab'])
							{
								$newTab = 'target="_new"';
							}
							
							/* Get the path to the file. */
							$fullPath = $_SERVER['DOCUMENT_ROOT'] . '/' . $row['url'];
							
							/* If the current page is the NavBar link, added the "navSelected" class to the item. */
							if ($fullPath == $currentPage)
							{
								$selected = ' navSelected';
								
								/* Also open up the secondary menu if there is one. */
								echo '<script>openSecondaryMenu(' . $row['id'] . ');</script>';
							}
							
							/* Print the Nav Item. */
							echo '<a href="/' . $row['url'] . '" onmouseover="openSecondaryMenu(' . $row['id'] . ');"' . $newTab . '><li class="navitem hidden-xs' . $selected . '">' . $row['display'] . '</li></a>';
						}
					}
					
					/* Print the Hamburger icon for mobile mode. */
					echo '<a href="/" onClick="openMobileNavBar(); return false;" class="text-right visible-xs-inline"><li class="navitem"><span class="glyphicon glyphicon-menu-hamburger"></span></li></a>';
				echo '</ul>';
				
				/* Create the secondary NavList. */
				echo '<div id="secondary-navbar">';
					echo '<ul id="secondary-list">';
						/* Prepare the query. */
						$this->db->where('parent', 0);
						$this->db->where("(`appid` = 0 OR `appid` = ?)", Array($this->gameID));
						$this->db->orderBy('listorder', 'asc');
						
						/* Execute the query. */
						$navbars = $this->db->get('navbar');
						
						/* Check the row count. */
						if ($this->db->count > 0)
						{
							/* Fetch the rows. */
							foreach ($navbars as $row)
							{
								/* Prepare the query. */
								$this->db->where('parent', $row['id']);
								$this->db->where("(`appid` = 0 OR `appid` = ?)", Array($this->gameID));
								$this->db->orderBy('listorder', 'asc');
								
								/* Execute the query. */
								$navbars2 = $this->db->get('navbar');
								
								/* Check the row count. */
								if ($this->db->count > 0)
								{
									echo '<div id="secondarylist-' . $row['id'] . '" class="secondary-menu">';
										/* Fetch the rows. */
										foreach ($navbars2 as $row2)
										{
											/* Check if the permissions column is not empty. */
											if (!empty($row2['permissions']))
											{
												/* If the user does not have permission to view the NavBar, move to the next item. */
												if (!$this->M['users']->hasGroupAccess($userInfo, 'navitem-' . $row2['id']))
												{
													continue;
												}
											}
											
											/* Declare default values. */
											$newTab = '';
											$selected = '';
											
											/* If it's a new tab, fill out the $newTab variable. */
											if ($row2['newtab'])
											{
												$newTab = 'target="_new"';
											}
											
											/* Get the path to the file. */
											$fullPath = $_SERVER['DOCUMENT_ROOT'] . '/' . $row2['url'];
											
											/* If the current page is the NavBar link, added the "navSelected" class to the item. */
											if ($fullPath == $currentPage)
											{
												$selected = ' navSelected';
												
												/* Also open up the secondary menu if there is one. */
												echo '<script>openSecondaryMenu(' . $row['id'] . ');</script>';
											}

											/* Print the NavBar secondary item. */
											echo '<a href="/' . $row2['url'] . '" ' . $newTab . '><li class="navitem nav-secondary' . $selected . '">' . $row2['display'] . '</li></a>';
										}
									echo '</div>';
								}
							}
						}
					echo '</ul>';
					
				echo '</div>';
				
				// Mobile NavList.
				echo '<div id="mobile-navbar" class="visible-xs">';
					echo '<div class="text-right" style="padding: 15px; margin-bottom: 3px;"><span class="glyphicon glyphicon-remove-circle" onClick="openMobileNavBar();"></span></div>';
					echo '<div class="text-center" id="mobile-menu-title"><h2>Menu</h2></div>';
					echo '<div id="mobile-navlist">';
						/* Prepare the query. */
						$this->db->where('parent', 0);
						$this->db->where("(`appid` = 0 OR `appid` = ?)", Array($this->gameID));
						$this->db->orderBy('listorder', 'asc');
						
						/* Execute the query. */
						$navbar = $this->db->get('navbar');
						
						/* Check the row count. */
						if ($this->db->count > 0)
						{
							/* Fetch the rows. */
							foreach ($navbar as $row)
							{
								/* Check if the permissions column is not empty. */
								if (!empty($row['permissions']))
								{
									/* If the user does not have permission to view the NavBar, move to the next item. */
									if (!$this->M['users']->hasGroupAccess($userInfo, 'navitem-' . $row['id']))
									{
										continue;
									}
								}
								
								/* Declare default values. */
								$newTab = '';
								$selected = '';
								$arrow = '';
								
								/* If it's a new tab, fill out the $newTab variable. */
								if ($row['newtab'])
								{
									$newTab = 'target="_new"';
								}
								
								/* Get the path to the file. */
								$fullPath = $_SERVER['DOCUMENT_ROOT'] . '/' . $row['url'];
								
								/* If the current page is the NavBar link, added the "navSelected" class to the item. */
								if ($fullPath == $currentPage)
								{
									$selected = ' navSelected';
								}
								
								/* Get the secondary items. */
								$this->db->where('parent', $row['id']);
								$this->db->where("(`appid` = 0 OR `appid` = ?)", Array($this->gameID));
								$this->db->orderBy('listorder', 'asc');
								
								/* Execute the query. */
								$secondaryNavItems = $this->db->get('navbar');
								
								/* Get the secondary amount. */
								$secondaryAmount = $this->db->count;
								
								/* Set the arrow if there are secondary items. */
								if ($secondaryAmount > 0)
								{
									$arrow = '<span id="mobile-arrow-' . $row['id'] . '"><span class="glyphicon glyphicon-chevron-right mobile-arrow" onClick="openMobileSecondaryList(' . $row['id'] . '); return false;"></span></span>';
								}
								
								/* Print the Nav Slot. */
								echo '<div class="mobile-navslot">';
									echo '<div class="row">';
										echo '<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">';
											echo '<a class="mobile-navitem' . $selected . '" href="/' . $row['url'] . '" ' . $newTab . '>' . $row['display'] . '</a>';
										echo '</div>';
										
										echo '<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">';
											echo $arrow;
										echo '</div>';
									echo '</div>';
								echo '</div>';
								
								/* Check the secondary amount. */
								if ($secondaryAmount > 0)
								{
									echo '<div class="mobile-secondarylist" id="mobile-secondarylist-' . $row['id'] . '">';
										/* Fetch the rows. */
										foreach ($secondaryNavItems as $row2)
										{
											/* Check if the permissions column is not empty. */
											if (!empty($row2['permissions']))
											{
												/* If the user does not have permission to view the NavBar, move to the next item. */
												if (!$this->M['users']->hasGroupAccess($userInfo, 'navitem-' . $row2['id']))
												{
													continue;
												}
											}
											
											/* Declare default values. */
											$newTab = '';
											$selected = '';
											
											/* If it's a new tab, fill out the $newTab variable. */
											if ($row2['newtab'])
											{
												$newTab = 'target="_new"';
											}
											
											/* Get the path to the file. */
											$fullPath = $_SERVER['DOCUMENT_ROOT'] . '/' . $row2['url'];
											
											/* If the current page is the NavBar link, added the "navSelected" class to the item. */
											if ($fullPath == $currentPage)
											{
												$selected = ' navSelected';
												
												/* Also open up the secondary menu if there is one. */
												echo '<script>openMobileSecondaryList(' . $row['id'] . ');</script>';
											}
											
											/* Print the secondary Nav Item. */
											echo '<div class="mobile-navslot mobile-navslot-secondary">';
												echo '<div class="row">';
													echo '<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">';
														echo '<a class="mobile-navitem' . $selected . '" href="/' . $row2['url'] . '" ' . $newTab . '>' . $row2['display'] . '</a>';
													echo '</div>';
												echo '</div>';
											echo '</div>';
										}
									
									echo '</div>';
								}
							}
						}
					echo '</div>';
				echo '</div>';
			echo '</div>';
		}
		
		// Loads the Logo.
		public function loadLogo()
		{
			// Currently, it's going to be text only.
			echo '<div id="logo">';
				echo '<div class="text-center">Browser.tf</div>';
			echo '</div>';
		}
		
		// Loads the footer.
		public function loadFooter()
		{
			/* Start the footer. */
			echo '<div id="footer">';
				/* Start the game selection form. */
				echo '<div class="form-group" id="gameselect">';
					/* Start the select option. */
					echo '<select id="game" class="custom-control" onChange="gameChange();">';
						/* Prepare the query. */
						$this->db->orderBy('listorder', 'asc');
						
						/* Execute the query. */
						$games = $this->db->get('games');
						
						/* Check the row count. */
						if ($this->db->count > 0)
						{
							/* Fetch the rows. */
							foreach ($games as $row)
							{
								/* Default values for the variables. */
								$selected = '';
								
								/* If this is the current game selected, add the "selected" attribute to the option. */
								if ($this->gameID == $row['appid'])
								{
									$selected = ' selected="selected"';
								}
								
								/* Output. */
								echo '<option value="' . $row['appid'] . '"' . $selected . '>' . $row['display'] . '</option>';	
							}
						}
					echo '</select>';
				echo '</div>';
				
				/* Start the statistics divider. */
				echo '<div id="statistics">';
					/* Retrieve the stats. */
					$stats = $this->M['stats']->getStats();
					
					/* Output. */
					echo 'Tracking <strong>' . $stats['manual'] . '</strong> community servers, <strong>' . $stats['valve'] . '</strong> Valve servers, <strong>' . $stats['stock-servers'] . '</strong> stock community servers, <strong>' . $stats['accounts'] . '</strong> Steam accounts, and <strong>' . $stats['favorites'] . '</strong> favorites!';
				echo '</div>';
				
				/* Other things on the footer. */
				echo '<p>"If Valve won\'t fix it, the community will."</p>';
				echo 'Website Made By ' . $this->M['users']->formatUser(7) . ' (Christian Deacon)';
			echo '</div>';
		}
		
		// From the web.
		public function json_readable_encode($in, $indent = 0, $from_array = false)
		{
			$_myself = __FUNCTION__;
			$_escape = function ($str)
			{
				return preg_replace("!([\b\t\n\r\f\"\\'])!", "\\\\\\1", $str);
			};

			$out = '';

			foreach ($in as $key=>$value)
			{
				$out .= str_repeat("\t", $indent + 1);
				$out .= "\"".$_escape((string)$key)."\": ";

				if (is_object($value) || is_array($value))
				{
					$out .= "\n";
					$out .= $this->json_readable_encode($value, $indent + 1);
				}
				elseif (is_bool($value))
				{
					$out .= $value ? 'true' : 'false';
				}
				elseif (is_null($value))
				{
					$out .= 'null';
				}
				elseif (is_string($value))
				{
					$out .= "\"" . $_escape($value) ."\"";
				}
				else
				{
					$out .= $value;
				}

				$out .= ",\n";
			}

			if (!empty($out))
			{
				$out = substr($out, 0, -2);
			}

			$out = str_repeat("\t", $indent) . "{\n" . $out;
			$out .= "\n" . str_repeat("\t", $indent) . "}";

			return $out;
		}
		
		// Loads the UserBar
		public function loadUserBar($userInfo = 0)
		{
			/* Start the UserBar. */
			echo '<div id="userbar">';
				/* Start the UserBar NavList. */
				echo '<ul id="userbar-navlist">';
				
						/* Check to see if the user's information is valid. */
						if ($userInfo)
						{
							/* Print their avatar. */
							echo '<li class="useritem"><img src="' . $userInfo['avatar'] . '" /></li>';
							
							/* Print their UserName .*/
							echo '<li class="useritem">' . $this->M['users']->formatUser($userInfo['id']) . '</li>';
							
							/* Print a "Log Out" item. */
							echo '<a href="/steamauth/logout.php"><li class="useritem">Log Out</li></a>';
						}
						else
						{
							/* Print a "Log In" item. */
							echo '<a href="/index.php?login"><li class="useritem"><img src="http://cdn.steamcommunity.com/public/images/signinthroughsteam/sits_small.png" title="Log In" alt="Log In" /></li></a>';
						}
				echo '</ul>';
			echo '</div>';
		}
		
		/* Inserts a log message into the database. */
		public function logMessage($module = 'main', $key = 'main', $message = '')
		{
			/* Prepare the query. */
			$data = Array
			(
				'module' => $module,
				'key' => $key,
				'message' => $message,
				'timeadded' => time()
			);
			
			/* Execute the query. */
			$this->db->insert('logs', $data);
		}
	};
?>