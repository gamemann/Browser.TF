<?php
	class cronjob_updateServers
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
			/* Include Source Query. */
			include_once($_SERVER['DOCUMENT_ROOT'] . '/SourceQuery/SourceQuery.class.php');
			
			/* Get the App ID declared within the options. */
			$optionsAppID = $this->appID;
			
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
				
				/* Prepare the query. */
				$this->db->orderBy('lastupdated', 'asc');
				
				/* Execute the query. */
				$servers = $this->db->get('`' . $doApp . '-servers`', 2000);
				
				/* Check the row count. */
				if ($this->db->count > 0)
				{
					/* Fetch the rows. */
					foreach ($servers as $server)
					{
						/* Define default values. */
						$serverInfo = Array();
						$online = 0;
						
						/* Create a Source Query. */
						$squery = new SourceQuery();
						
						/* Try the query. */
						try
						{
							$squery->Connect($server['ip'], $server['port'], 1);
							$serverInfo = $squery->GetInfo();
						}
						catch (Exception $e)
						{
						
						}
						
						/* Check the result. */
						if (!is_array($serverInfo) || !isset($serverInfo['MaxPlayers']))
						{
							$serverInfo = array
							(
								'HostName' => '',
								'MaxPlayers' => 0,
								'Players' => 0,
								'GameTags' => '',
								'Map' => ''
							);
						}
						else
						{
							/* Check if the server is hosting the correct game. */
							if ($serverInfo['AppID'] > 0 && $serverInfo['AppID'] != $doApp)
							{
								/* Delete the server from the list. */
								
								/* Prepare the query. */
								$this->db->where('id', $server['id']);
								
								/* Execute the query. */
								$check = $this->db->delete('`' . $doApp . '-servers`');
								
								/* Check the result. */
								if ($check)
								{
									/* Log a message. */
									$this->M['main']->logMessage('crons', 'updateServers::BadGameRemoval', $server['ip'] . ':' . $server['port'] . ' (' . $server['id'] . ') removed for having the wrong App ID. (' . $doApp . '!=' . $serverInfo['AppID'] . ')');
								}
							}
							
							$online = 1;
						}
						
						/* Encode the server data. */
						$serverData = json_encode($serverInfo, JSON_PRETTY_PRINT);
						
						/* Update the server in the database. */
						
						/* Prepare the query. */
						$this->db->where('id', $server['id']);
						$data = Array
						(
							'info' => $serverData,
							'online' => $online,
							'lastupdated' => time()
						);

						
						/* Execute the query. */
						$this->db->update('`' . $doApp . '-servers`', $data);
					}
				}
			}
		}
	}
?>