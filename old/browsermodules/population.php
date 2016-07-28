<?php
	class module_population
	{
		public $db;
		public $lastAmount;
		public $browser;
		
		public function init($newdb = 0, $b = 0)
		{
			// Receive the database.
			if ($newdb)
			{
				$this->db = $newdb;
			}
			
			if ($b)
			{
				$this->browser = $b;
			}
			
			// Config
			$this->lastAmount = 30; 			// Receive the population in the last x days (default: 30).
			$this->minimumMaxPlayers = 16;		// The minimum maxplayers to count.
			$this->playersBoost = 0.01;			// How much of a boost should we give servers (this means, highler slot count = better ranking). I want to use this because it is generally harder to fill up larger servers (more slots = more room).
		}
		
		public function getRating($sIP = 0, $sPort = 0)
		{
			$toReturn = -1;
			
			$total = 0;
			$max = 0;
			
			if ($this->browser->getServerOwner($sIP, $sPort) > 0)
			{
				// Now let's get the amount of players.
				$query = $this->db->query("SELECT * FROM `population` WHERE `ip`='" . $sIP . "' AND `port`=" . $sPort . " AND `timestamp` > " . ($this->lastAmount * 86400));
				
				if ($query && $query->num_rows > 0)
				{
					while ($row = $query->fetch_assoc())
					{
						if ($row['populationmax'] >= $this->minimumMaxPlayers)
						{
							$total += $row['population'];
							
							// Boosting
							$total += $row['populationmax'] * $this->playersBoost;
							
							$max += $row['populationmax'];
						}
					}
				}
				
				if ($total > 0 && $max > 0)
				{
					$toReturn = $total / $max;	// Example: 11/24 (players/maxplayers) = 0.45xxxxx
					$toReturn *= 100;			// Now, it's 0.45xxxxxx * 100 (rounded) = 45. 45/100.
				}
			}
			
			return $toReturn;
		}
		
	}
?>