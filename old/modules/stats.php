<?php
	class stats
	{	
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
		
		public function getStats($game=440)
		{
			$toReturn = array();
			
			/* 
				Stock Server Browser.
			*/
			
			/* Prepare the query. */
			$this->db->where('appid', $this->gameID);
			
			/* Execute the query. */
			$query = $this->db->get('approved_servers');
			
			/* Return the row count. */
			$toReturn['stock-servers'] = $this->db->count;
			
			/* 
				Accounts.
			*/
			
			/* Execute the query. */
			$query = $this->db->get('accounts');
			
			/* Return the row count. */
			$toReturn['accounts'] = $this->db->count;		
			
			/* 
				Favorites.
			*/
			
			/* Prepare the query. */
			$this->db->where('appid', $this->gameID);
			
			/* Execute the query. */
			$query = $this->db->get('favorites');
			
			/* Return the row count. */
			$toReturn['favorites'] = $this->db->count;
			
			/* 
				Master Server.
			*/
			
			$masterServers = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/master/' . $game . '.json');
			$masterServers = json_decode($masterServers, true);
			
			if (is_array($masterServers))
			{
				$toReturn['master'] = count($masterServers);
			}
			else
			{
				$toReturn['master'] = 0;
			}
			
			/* 
				Valve Servers.
			*/
			
			$valveServers = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/master/' . $this->gameID . '_valvelist.json');
			
			$valveServers = json_decode($valveServers, true);
			
			$toReturn['valve'] = count($valveServers);
			
			/* 
				Community servers.
			*/
			
			/* Prepare the query. */
			$this->db->where('online', 1);
			
			/* Execute the query. */
			$query = $this->db->get('`' . $this->gameID . '-servers`');
			
			/* Return the row count. */
			$toReturn['manual'] = $this->db->count;
			
			return $toReturn;
		}
		
		public function getValveCount()
		{
			$servers = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/master/' . $this->gameID . '_valvelist.json');
			
			$servers = json_decode($servers, true);
			
			return count($servers);
		}
		
	}
?>