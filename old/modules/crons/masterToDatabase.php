<?php
	class cronjob_masterToDatabase
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
				
				/* Receive the JSON list of servers for the specific game. */
				$serversTemp = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/master/' . $doApp . '.json');
				
				/* Decode the JSON string. */
				$servers = json_decode($serversTemp, true);
				
				/* Check the array. */
				if ($servers && is_array($servers))
				{
					/* Fetch the rows. */
					foreach ($servers as $server)
					{
						/* Explode the string. ($info[0] = Server IP, $info[1] = Server Port) */
						$info = explode(":", $server);
						
						/* Create a new SourceQuery. */
						$squery = new SourceQuery();
						
						/* Try stuff. */
						try
						{
							$squery->Connect($info[0], $info[1], 1);
							$serverInfo = $squery->GetInfo();
						}
						catch (Exception $e)
						{
						
						}
						
						/* Check the $serverInfo array. */
						if ($serverInfo && isset($serverInfo['MaxPlayers']))
						{
							/* Check the HostName, AppID, etc. */
							if ($serverInfo['MaxPlayers'] < 1 || empty($serverInfo['Map']) || stristr($serverInfo['HostName'], 'Valve') || $serverInfo['AppID'] != $doApp)
							{
								continue;
							}
							
							/* Check if the server exists in the database. */
							
							/* Prepare the query. */
							$this->db->where('ip', $info[0]);
							$this->db->where('port', $info[1]);
							
							/* Execute the query. */
							$check = $this->db->getOne('`' . $doApp . '-servers`');
							
							/* Check the result. */
							if (!$check)
							{
								/* Insert the server into the database. */
								
								/* Prepare the query. */
								$data = Array
								(
									'ip' => $info[0],
									'port' => $info[1],
									'online' => 0,
									'lastupdated' => 0
								);
								
								/* Execute the query. */
								$check2 = $this->db->insert('`' . $doApp . '-servers`', $data);
								
								if ($check2)
								{
									/* Log a message! */
									$this->M['main']->logMessage('crons', 'masterToDatabase::serverAdded', 'Successfully added ' . $info[0] . ':' . $info[1] . ' to the ' . $doApp . ' table!');
								}
							}
						}
					}
				}
			}
		}
	}
?>