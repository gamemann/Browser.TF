<?php
	// All functions go in here.
	class browser
	{
		protected $defaultRatings;
		protected $ratingColors;
		protected $modulesList;
		protected $module;
		protected $notifyList;
		protected $maxRating;
		protected $defaultServerRating;
		protected $gameAppID;
		protected $allowManual;
		protected $storeType;
		
		public function init()
		{
			// Other Modules.
			global $M;
			$this->M = $M;
			
			$this->db = $this->M['mysql']->receiveDataBase();
			
			// Configuration.
			$this->defaultRatings = array
			(
				'performance',
				'quality',
				'community'
			);
			
			$this->ratingColors = array
			(
				'good' => array
				(
					'min' => '90',
					'max' => '100',
					'color' => '#10F700'
				),
				
				'average' => array
				(
					'min' => '75',
					'max' => '90',
					'color' => '#C9FF00'
				),				
				
				'poor' => array
				(
					'min' => '60',
					'max' => '75',
					'color' => '#D27300'
				),				
				
				'terrible' => array
				(
					'min' => '0',
					'max' => '60',
					'color' => '#D42020'
				),
			);
			
			// First, create the modules list (this is configurable).
			$this->modulesList = array
			(
				'uptime',
			);
			
			// (this is not configurable), now make the module.
			foreach ($this->modulesList as $t)
			{
				 require_once($_SERVER['DOCUMENT_ROOT'] . '/browsermodules/' . $t . '.php');
				 
				 $class = 'module_' . $t;
				 $this->module[$t] = new $class;
				 $this->module[$t]->init($this->db, $this);
			}
			
			// Notification List (requests@browser.tf & reports@browser.tf & support@browser.tf are automatically added).
			$this->notifyList = array
			(
			
			);
			
			// Maximum rating
			$this->maxRating = 10;	// Recommend keeping this at 10 unless you can find a correct formula.
			
			// Default Server rating
			$this->defaultServerRating = 50;
			
			// Get the Game ID.
			$this->gameAppID = 440;
			$this->getGameAppID();
			
			// Allow manual update-mode (stores all servers information in text files)
			$this->allowManual = true;
			
			// Storing type using manual mode. (0 = MySQL, 1 = Files located in server/<ip>:<port>.txt)
			$this->storeType = 0;
			
			/* Add cron jobs. */
			$this->M['crons']->registerCronJob('json_fullbrowser', 'updateJSON', 'JSON - Full Browser', 'Updates the full server browser with JSON.', 'Christian Deacon', Array ('type' => 0));
		}
		
		public function calculateRating($serverIP, $serverPort, $exclude='', $bModules = true)
		{
			$maxrate = 0;
			$ratings = 0;
			
			/* Prepare the query. */
			$this->db->where('ip', $serverIP);
			$this->db->where('port', $serverPort);
			
			/* Execute the query. */
			$rate = $this->db->get('ratings');
			
			/* Check the row count. */
			if ($this->db->count > 0)
			{
				/* Fetch the rows. */
				foreach ($rate as $row)
				{
					/* Add up the ratings. */
					$maxrate += $row['rating'] * $this->maxRating;
					$ratings++;
				}
			}
			
			// Now to receive custom ratings (modules).
			if ($bModules)
			{
				$modules = $this->modulesList;
				
				if (!empty($exclude))
				{
					$toRemove = array();
					if (is_array($exclude))
					{
						foreach($exclude as $value)
						{
							array_push($toRemove, $value);
						}
					}
					else
					{
						array_push($toRemove, $exclude);
					}
					
					$modules = array_diff($modules, $toRemove);
				}
				
				foreach ($modules as $m)
				{
					$rating = $this->module[$m]->getRating($serverIP, $serverPort);
					
					if ($rating > -1)
					{
						$maxrate += $rating;
						$ratings++;
					}
				}
			}
				
			// Now return the final average.
			if ($maxrate > 0 && $ratings > 0)
			{
				return round($maxrate / $ratings, 0, PHP_ROUND_HALF_UP);
			}
			else
			{
				return $this->defaultServerRating;
			}
		}
		
		public function calculateAllRatings($sIP, $sPort, $exclude='')
		{
			$settings = $this->defaultRatings;
			$modules = $this->modulesList;
			$overall = 0;
			
			$toReturn = array();
		
			/* Prepare the query. */
			$this->db->where('ip', $sIP);
			$this->db->where('port', $sPort);
			
			/* Execute the query. */
			$rate = $this->db->get('ratings');
			
			/* Check the row count. */
			if ($this->db->count > 0)
			{
				$toReturn['reviews'] = array
				(
					'display' => 'Reviews',
					'rating' => 0,
					'aid' => '-1'
				);
				
				/* Fetch the rows. */
				foreach ($rate as $row)
				{
					/* Add up the ratings. */
					$toReturn['reviews']['rating']	+= $row['rating'] * 10;
					$overall++;
				}
				
				$toReturn['reviews']['rating'] = $toReturn['reviews']['rating'] / $overall;
			}
			
			// Now to receive custom ratings (modules).
			if (!empty($exclude))
			{
				$toRemove = array();
				if (is_array($exclude))
				{
					foreach($exclude as $value)
					{
						array_push($toRemove, $value);
					}
				}
				else
				{
					array_push($toRemove, $exclude);
				}
				
				$modules = array_diff($modules, $toRemove);
			}
			
			foreach ($modules as $m)
			{
				$toReturn[$m] = array
				(
					'display' => $m,
					'rating' => $this->module[$m]->getRating($sIP, $sPort),
					'aid' => '-1'
				);
			}
			
			// Now return the final array.
			return $toReturn;
		}
		
		public function getModulesList()
		{
			return $this->modulesList;
		}
		
		public function displayRating($rating=0)
		{
			$toReturn = $rating;
			
			// Now get the correct color!
			foreach ($this->ratingColors as $key => $value)
			{
				if ($rating <= $value['max'] && $rating >= $value['min'])
				{
					// We have the correct set!
					$toReturn = '<span class="rating=' . $key . '" style="color: ' . $value['color'] . ';">' . $rating . '</span>';
				}
			}
			
			return $toReturn;
		}
		
		public function isFavorited($userInfo = 0, $sIP, $sPort)
		{	
			if ($userInfo)
			{
				/* Prepare the query. */
				$this->db->where('aid', $userInfo['id']);
				$this->db->where('ip', $sIP);
				$this->db->where('port', $sPort);
				
				/* Execute the query. */
				$temp = $this->db->get('favorites');
				
				/* Check the row count. */
				if ($this->db->count > 0)
				{
					return true;
				}
			}
			
			return false;
		}		
		
		public function isOwner($userInfo = 0, $sIP, $sPort)
		{
			if ($userInfo == 0 || $sIP == 0)
			{
				return false;
			}
			
			if ($userInfo)
			{
				/* Prepare the query. */
				$this->db->where('aid', $userInfo['id']);
				$this->db->where('ip', $sIP);
				$this->db->where('port', $sPort);
				
				/* Execute the query. */
				$temp = $this->db->get('owned_servers');
				
				/* Check the row count. */
				if ($this->db->count > 0)
				{
					return true;
				}
			}
			
			return false;
		}
		
		public function notifyMail($type = 1, $subject = '', $message = '')
		{
			if ($type == 1)
			{
				// Requests
				$to = 'requests@browser.tf';
			}
			elseif ($type == 2)
			{
				// Reports
				$to = 'reports@browser.tf';
			}
			elseif ($type == 3)
			{
				// Support
				$to = 'support@browser.tf';
			} // ... None, use notication list only.
			
			mail($to, $subject, $message);
			
			foreach ($this->notifyList as $t)
			{
				mail($t, $subject, $message);
			}
		}
		
		public function isReviewed($userInfo = 0, $sIP, $sPort)
		{
			$reviewed = false;
			
			if ($userInfo)
			{
				/* Prepare the query. */
				$this->db->where('aid', $userInfo['id']);
				$this->db->where('ip', $sIP);
				$this->db->where('port', $sPort);
				
				/* Execute the query. */
				$temp = $this->db->get('ratings');
				
				/* Check the row count. */
				if ($this->db->count > 0)
				{
					$reviewed = true;
				}
			}
			
			return $reviewed;
		}
		
		public function getMaxRating()
		{
			return $this->maxRating;
		}
		
		public function getReviewsCount($sIP, $sPort)
		{	
			/* Prepare the query. */
			$this->db->where('ip', $sIP);
			$this->db->where('port', $sPort);
			
			/* Execute the query. */
			$temp = $this->db->get('ratings');
			
			/* Return the row count. */
			return $this->db->count;
		}
		
		public function getServerOwner($sIP, $sPort)
		{
			// Returns 0 if not owned.
			$toReturn = 0;
			
			/* Prepare the query. */
			$this->db->where('ip', $sIP);
			$this->db->where('port', $sPort);
			
			/* Execute the query. */
			$server = $this->db->get('owned_servers');
			
			/* Check the row count. */
			if ($this->db->count > 0)
			{
				/* Fetch the rows. */
				foreach ($server as $row)
				{
					/* Return the owner of the server. */
					$toReturn = $row['aid'];
				}
			}
			
			return $toReturn;
		}
		
		public function getGameID()
		{
			return $this->gameAppID;
		}
		
		public function getManual()
		{
			return $this->allowManual;
		}
		
		public function getStoreType()
		{
			return $this->storeType;
		}
		
		public function printServerToTable($userInfo, $row)
		{
			// Basically prints the servers to the table.
			$info = str_replace('\\\'', '\'', $row['info']);
			$serverInfo = json_decode($info, true);
			
			if (!$serverInfo || $serverInfo['MaxPlayers'] < 1 || empty($serverInfo['Map']) || stristr($serverInfo['HostName'], 'Valve'))
			{
				return false;
			}
			
			// Let's get to it!
			if ($userInfo)
			{
				if ($this->isFavorited($userInfo, $row['ip'], $row['port']))
				{
					$toFav = '<span class="glyphicon glyphicon-star custom-unfav-star" title="Un-Favorite" onclick="unFavoriteServer(\'' . $row['ip'] . '\', ' . $row['port'] . ', 1, ' . $row['id'] . ');"></span>';
				}
				else
				{
					$toFav = '<span class="glyphicon glyphicon-star custom-fav-star" title="Favorite" onclick="favoriteServer(\'' . $row['ip'] . '\', ' . $row['port'] . ', 1, ' . $row['id'] . ');"></span>';
				}
			}
			else
			{
				$toFav = '';
			}
			
			$rating = $this->calculateRating($row['ip'], $row['port'], array('population'));
			
			// Add Server Row.
			echo '<tr>';
				echo '<td><a href="/pages/servers/viewserver.php?ip=' . $row['ip'] . '&port=' . $row['port'] . '">' . $serverInfo['HostName'] . '</a></td>';
				echo '<td>' . $serverInfo['Players'] . '</td>';
				echo '<td>' . $serverInfo['MaxPlayers'] . '</td>';
				echo '<td>' . $serverInfo['Map'] . '</td>';
				echo '<td>' . $row['ip'] . '</td>';
				echo '<td>' . $row['port'] . '</td>';
				echo '<td>' . $this->displayRating($rating) . '</td>';
				echo '<td><a href="steam://connect/' . $row['ip'] . ':' . $row['port'] . '"><span class="glyphicon glyphicon-play custom-connect"></span></a> <span id="favoriteItem-' . $row['id'] . '">' . $toFav . '</span></td>';
				echo '<td>' . $serverInfo['GameTags'] . '</td>';
			echo '</tr>';
		}
		
		public function getGameAppID()
		{
			if (isset($_GET['appid']))
			{
				$this->gameAppID = $_GET['appid'];
			}
			elseif (isset($_POST['appid']))
			{
				$this->gameAppID = $_GET['appid'];
			}
			elseif (isset($_COOKIE['appid']))
			{
				$this->gameAppID = $_COOKIE['appid'];
			}
		}
		
		/* Returns an array of games. */
		public function getGameList()
		{
			$toReturn = Array();
			
			/* Prepare the query. */
			$this->db->orderBy('listorder', 'asc');
			
			/* Execute the query. */
			$games = $this->db->get('games');
			
			/* Fetch the rows. */
			foreach ($games as $game)
			{
				$toReturn[] = Array
				(
					'appid' => $game['appid'],
					'display' => $game['display']
				);
			}
			
			return $toReturn;
		}
	};
?>