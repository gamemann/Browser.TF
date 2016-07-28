<?php
	class cronjob_updateMasters
	{
		protected $db;
		protected $appID;
		
		public function __construct()
		{
			/* Receive global modules. */
			global $M;
			$this->M = $M;
			
			/* Receive the database handle. */
			$this->db = $this->M['mysql']->receiveDataBase();
			
			/* Get the App ID. */
			$this->appID = $this->M['browser']->getGameID();
		}
		
		/* Called when the cron job executes. */
		public function execute($options = Array())
		{	
			/* First get the type. (0 = Full Server Browser & 1 = Stock Server Browser) */
			$type = 0;
			
			/* Get the App ID declared within the options. */
			$optionsAppID = $this->appID;
			
			if (isset($options['type']))
			{
				$type = $options['type'];
			}
			
			/* Update the App ID if it's set in the options. */
			if (isset($options['appid']))
			{
				$optionsAppID = $options['appid'];
			}
			
			/* Get the game list. */
			$games = $this->M['browser']->getGameList();
			
			/* Loop through every game. */
			foreach ($games as $game)
			{
				/* First, check if we do the game. */
				if (($optionsAppID != 0 && $optionsAppID != $game['appid']))
				{
					continue;
				}
				
				$doApp = $game['appid'];
				
				/* Include Steam Condenser. */
				include_once($_SERVER['DOCUMENT_ROOT'] . '/condenser/steam-condenser.php');
				
				/* Turn error reporting off. */
				error_reporting(E_ERROR);
				
				/* Define default values. */
				$servers = Array();
				
				/* Create a new Master Server. */
				$master = new MasterServer(MasterServer::SOURCE_MASTER_SERVER);
				
				/* Try something. */
				try
				{
					$servers = $master->getServers(MasterServer::REGION_ALL, "\\appid\\{$doApp}", true);
				}
				catch (TimeoutException $e)
				{
				
				}
				
				/* Check the $servers Array. */
				if ($servers)
				{
					/* Remove Valve servers from the list. */
					$valveServers = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/master/' . $doApp . '_valvelist.json');
					$valveServers = json_decode($valveServers, true);
					
					$servers = array_diff($servers, $valveServers);
					
					/* Remove blacklisted servers. */
					$blServers = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/master/' . $doApp . '_blacklist.json');
					$blServers = json_decode($blServers, true);
					
					$servers = array_diff($servers, $blServers);
					
					/* Check the $servers count. */
					if (count($servers) > 0)
					{
						/* Encode the data. */
						$data = json_encode($servers, JSON_PRETTY_PRINT);
						
						/* Put the data into the master file. */
						file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/master/' . $doApp . '.json', $data);
						
						/* Echo the output. */
						echo $doApp . ': List updated.<br />';
					}
					else
					{
						echo $doApp . ': Master server timing out (2).<br />';
					}
				}
				else
				{
					echo $doApp . ': Master server timing out (1).<br />';
				}
			}
		}
	}
?>