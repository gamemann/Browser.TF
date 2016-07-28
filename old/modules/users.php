<?php
	class users
	{	
		protected $db;
		
		public function init()
		{
			// Other Modules.
			global $M;
			$this->M = $M;
			
			$this->db = $this->M['mysql']->receiveDataBase();
		}
		
		public function getUserInfo($id = 0)
		{
			if ($id > 0)
			{
				$steamprofile = array();
				
				/* Prepare the query. */
				$this->db->where('id', $id);

				/* Execute the query. */
				$profile = $this->db->get('accounts');
				
				/* Check the row count. */
				if ($this->db->count > 0)
				{
					/* Fetch the rows. */
					foreach ($profile as $row)
					{
						$steamprofile = json_decode($row['steaminfo'], true);
						$steamprofile['group'] = $row['group'];
						$steamprofile['lastlogin'] = $row['lastlogin'];
					}
				}
				
				return $steamprofile;
			}
			else
			{
				if (!isset($_SESSION['steamid']))
				{
					return 0;
				}
				else
				{
					@include_once ($_SERVER['DOCUMENT_ROOT'] . '/steamauth/userInfo.php');
					
					/* Prepare the query. */
					$this->db->where('steamid', $steamprofile['steamid']);
					
					/* Execute the query. */
					$user = $this->db->getOne('accounts');
					
					/* Check the row count. */
					if ($this->db->count > 0 && $user)
					{
						
						/* Update the user. */
						$steamprofile['id'] = $user['id'];
						$steamprofile['group'] = $user['group'];
						
						/* Prepare the query. */
						$this->db->where('id', $user['id']);
						
						$data = Array
						(
							'lastlogin' => time()
						);
						
						/* Execute the query. */
						$this->db->update('accounts', $data);
					}
					else
					{
						/* Insert the user. */
						
						/* Prepare the query. */
						$data = Array
						(
							'steamid' => $steamprofile['steamid'],
							'lastlogin' => time(),
							'group' => 1
						);
						
						/* Execute the query. */
						$this->db->insert('accounts', $data);
					}
					
					
					/* Get the account information. */

					if (!$user)
					{
						/* Prepare the query. */
						$this->db->where('steamid', $steamprofile['steamid']);
						
						/* Execute the query. */
						$user2 = $this->db->getOne('accounts');
						
						/* Update the information. */
						if ($user2)
						{
							$steamprofile['id'] = $user2['id'];
							$steamprofile['group'] = $user2['group'];
						}
					}
					
					/* One more thing, update the `steaminfo` column. */
					
					/* Prepare the query. */
					$toJSON = json_encode($steamprofile);
					$this->db->where('id', $steamprofile['id']);
					$data = Array
					(
						'steaminfo' => $toJSON
					);
					
					/* Execute the query. */
					$this->db->update('accounts', $data);
					
					/* Check the other stuff. */
					if (!isset($steamprofile['id']))
					{
						$steamprofile['id'] = -1;
					}
					
					if (!isset($steamprofile['group']))
					{
						$steamprofile['group'] = 1;
					}
					
					return $steamprofile;
				}
			}
		}
		
		public function formatUser($id)
		{
			$username = 'Unknown';
			$group = -1;
			$toReturn = '';
			$style = '';
			$info = array();
			
			/* Prepare the query. */
			$this->db->where('id', $id);
			
			/* Execute the query. */
			$user = $this->db->getOne('accounts');
			
			/* Check the user. */
			if ($user)
			{
				$info = json_decode($user['steaminfo'], true);
				
				if (!empty($info['personaname']))
				{
					$username = $info['personaname'];
				}
				
				$group = $user['group'];
			}
			
			
			/* Get the group format. */
			
			/* Prepare the query. */
			$this->db->where('id', $group);
			
			/* Execute the query. */
			$groupFormat = $this->db->getOne('groups');
			
			/* Check the group. */
			if ($groupFormat)
			{
				$style = $groupFormat['style'];
			}
			
			// Let the formatting begin!
			if (!empty($info['personaname']))
			{
				$toReturn = '<span class="username"><a href="' . $info['profileurl'] . '" target="_new" class="formatuser-link"><span style="' . $style . '">' . $username . '</span></a></span>';
			}
			else
			{
				$toReturn = $username;
			}
			
			return $toReturn;
		}
		
		// Group Access.
		public function hasGroupAccess($userInfo=0, $key)
		{	
			$toReturn = false;
			
			if ($userInfo)
			{
				$group = $userInfo['group'];
			}
			else
			{
				$group = 0;
			}
			
			/* Prepare the query. */
			$this->db->where('`key`', $key);
			
			/* Execute the query. */
			$permissions = $this->db->get('permissions');
			
			/* Check the row count. */
			if ($this->db->count > 0)
			{
				/* Fetch the rows. */
				foreach ($permissions as $row)
				{
					if (!empty($row['permissions']))
					{
						$groups = json_decode($row['permissions'], true);
					}
					else
					{
						return true;
					}
				}
			}
			else
			{
				return true;
			}
			
			foreach ($groups as $groupID)
			{
				if ($group == $groupID)
				{
					$toReturn = true;
				}
			}
			
			return $toReturn;
		}
	}
?>