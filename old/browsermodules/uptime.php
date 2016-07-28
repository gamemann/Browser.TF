<?php
	class module_uptime
	{
		public $db;
		public $lastAmount;
		
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
			
			$this->lastAmount = 30;		// Amount of time to track. (default: 30 days)
		}
		
		public function getRating($sIP = 0, $sPort = 0)
		{
			$toReturn = -1;
			
			$total = 0;
			$max = 0;
			
			// Check if the server is owned.
			
			if ($this->browser->getServerOwner($sIP, $sPort) > 0)
			{
				// Now let's do this!
				$query = $this->db->query("SELECT * FROM `population` WHERE `ip`='" . $sIP . "' AND `port`=" . $sPort . " AND `timestamp` > " . ($this->lastAmount * 86400));
				
				if ($query && $query->num_rows > 0)
				{
					while ($row = $query->fetch_assoc())
					{
						$total += $row['online'];
						$max++;				
					}
				}
				
				if ($total > 0 && $max > 0)
				{
					$toReturn = $total / $max;	// Example: 12/14 (0.85)
					$toReturn *= 100;			// Example: 0.85 * 100 = 85 (%) uptime.
				}
			}
			return $toReturn;
		}
		
	}
?>