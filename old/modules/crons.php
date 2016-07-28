<?php
	class crons
	{
		protected $db;
		protected $crons;
		
		public function __construct()
		{
			/* Set the default values. */
			$this->crons = array();
		}
		
		public function init()
		{
			// Other Modules.
			global $M;
			$this->M = $M;
			
			// Variables.
			$this->db = new mysqli('myHost', 'myUser', 'myPassword', 'myDatabase');
		}
		
		/* Registers a public cron job. This function doesn't do anything special. */
		public function registerCronJob($name = 'cronjobtest', $cron = 'myCron', $displayName = 'Cron Job Test', $description = '', $author = '', $options = Array())
		{
			/* Register the cron job. */
			$this->crons[$name] = array
			(
				'name' => $name,
				'cron' => $cron,
				'displayName' => $displayName,
				'description' => $description,
				'author' => $author,
				'options' => $options
			);
		}
		
		/* Executes a cron job. */
		public function executeCronJob($cron = 'cronjobtest', $options = Array())
		{
			/* Include the cron job file. */
			@include_once ($_SERVER['DOCUMENT_ROOT'] . '/modules/crons/' . $cron . '.php');
			
			/* Get the class name. */
			$className = 'cronjob_' . $cron;
			
			/* Check if the class exists. */
			if (class_exists($className))
			{
				/* Declare the new class. */
				$cronJob = new $className;
				
				/* Check if the "execute" function exists. */
				if (method_exists($cronJob, 'execute'))
				{
					/* Execute the cron job. */
					$cronJob->execute($options);
				}
			}
		}
	}
	
	/* 
		2-3-16: Notes for myself. Please remove after implemented. 
		--------------------------------------------------------
		Make a MySQL-based Cron Job system. Columns include: "id", "name", "cron", "displayName", "description", "author", "options" (JSON Encoded), "lastupdated", "timeadded", "ranevery"
		One cron jon should be added to the actual cPanelX control panel (e.g. /scripts/executeCrons.php). This basically gets all the cron jobs. It then checks if crons are ready to run (if ((time() - "lastupdated") > "ranevery")). If that succeeds, it executes the cron.
	*/
	
?>