<?php
	class cronjob_updateJSON
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
				
				/* Define the default values. */
				$servers = Array();
				$data = Array();
				
				/* Full server browser. */
				if ($type == 0)
				{
					/* Set the file name. */
					$fileName = $doApp . '-fullbrowser.json';
					
					/* Prepare the query. */
					$this->db->where('online', 1);
					
					/* Execute the query. */
					$fservers = $this->db->get('`' . $doApp . '-servers`');
					
					/* Check the row count. */
					if ($this->db->count > 0)
					{
						/* Fetch the rows. */
						foreach ($fservers as $row)
						{
							$serverInfo = json_decode($row['info'], true);
							
							print_r($serverInfo, false);
							echo '<br /><br />';
							
							if (!$serverInfo || $serverInfo['MaxPlayers'] < 1 || empty($serverInfo['Map']) || stristr($serverInfo['HostName'], 'Valve') || $serverInfo['AppID'] != $doApp)
							{
								continue;
							}
							
							$rating = $this->M['browser']->calculateRating($row['ip'], $row['port'], array('population'));
							
							$servers[] = array
							(
								'<a href="/pages/servers/viewserver.php?ip=' . $row['ip'] . '&port=' . $row['port'] . '">' . $serverInfo['HostName'] . '</a>', 
								$serverInfo['Players'],
								$serverInfo['MaxPlayers'],
								addslashes($serverInfo['Map']),
								$row['ip'],
								$row['port'],
								$this->M['browser']->displayRating($rating),
								'<a href="steam://connect/' . $row['ip'] . ':' . $row['port'] . '"><span class="glyphicon glyphicon-play custom-connect"></span></a>',
								addslashes($serverInfo['GameTags'])
							);
						}
					}
				}
				/* Stock Server Browser. */
				elseif ($type == 1)
				{
					/* Set the file name. */
					$fileName = $doApp . '-stockbrowser.json';
					
					/* Prepare the query. */
					$this->db->where('enabled', 1);
					
					/* Execute the query. */
					$tempservers = $this->db->get('approved_servers');
					
					/* Check the row count. */
					if ($this->db->count > 0)
					{
						/* Fetch the rows. */
						foreach ($tempservers as $temprow)
						{
							/* Prepare the query. */
							$this->db->where('ip', $temprow['ip']);
							$this->db->where('port', $temprow['port']);
							
							/* Execute the query. */
							$row = $this->db->getOne('`' . $doApp . '-servers`');

							if ($row)
							{
								$serverInfo = json_decode($row['info'], true);
								
								if ($serverInfo['MaxPlayers'] < 1)
								{
									continue;
								}
								
								$rating = $this->M['browser']->calculateRating($row['ip'], $row['port'], array('population'));
								
								$servers[] = array
								(
									'<a href="/pages/servers/viewserver.php?ip=' . $row['ip'] . '&port=' . $row['port'] . '">' . $serverInfo['HostName'] . '</a>', 
									$serverInfo['Players'],
									$serverInfo['MaxPlayers'],
									$serverInfo['Map'],
									$row['ip'],
									$row['port'],
									$this->M['browser']->displayRating($rating),
									'<a href="steam://connect/' . $row['ip'] . ':' . $row['port'] . '"><span class="glyphicon glyphicon-play custom-connect"></span></a>',
									addslashes($serverInfo['GameTags'])
								);
							}
						}
					}
				}
				
				$data['data'] = $servers;
				
				$jsonString = json_encode($data);
				
				/* Put the information into the JSON file. */
				if (count($data['data']) > 0 && !empty($jsonString))
				{
					file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/json/' . $fileName, $jsonString);
				}
			}
		}
	}
?>