<?php
	class mysql
	{
		protected $db;
		protected $queries;
		
		public function init()
		{
			/* Load the global modules variable. */
			global $M;
			$this->M = $M;
			
			/* Include the MysqliDB class. */
			@include_once($_SERVER['DOCUMENT_ROOT'] . '/MysqliDB/MysqliDb.php');
			
			$this->db = new MysqliDB('myHost', 'myUser', 'myPassword', 'myDatabase', 3306, null);
		}
		
		public function receiveDataBase()
		{
			return $this->db;
		}
	}
?>