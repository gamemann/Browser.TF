<?php
	session_start();
	
	// Include the main file. Let's start!
	require_once($_SERVER['DOCUMENT_ROOT'] . '/init.php');
	
	// Get the DataBase.
	$db = $M['mysql']->receiveDataBase();
	
	// Set the time-zone to New York (for myself).
	date_default_timezone_set('America/New_York');
	
	/* CUSTOM THINGS */
	
	// SteamAuth.
	require_once($_SERVER['DOCUMENT_ROOT'] . '/steamauth/steamauth.php');
	
	// User's information.
	$userInfo = $M['users']->getUserInfo();
	
	// APPID
	$appID = $M['browser']->getGameID();
	
	/* Time to set up the page! */
	
	/* First, select all the cron jobs. */ 
	
	/* Prepare the query. */
	$db->where('enabled', 1);
	$i = 0;
	
	/* Execute the query. */
	$crons = $db->get('cronjobs');
	
	/* Check the row count. */
	if ($db->count > 0)
	{
		/* Fetch the rows. */
		foreach ($crons as $cron)
		{
			/* Get the amount of time it has been since the cron was last ran. */
			$timeSince = time() - $cron['lastran'];
			
			/* Receive the interval for the cron job. */
			$timeInterval = $cron['ranevery'];
			
			/* Check if the $timeSince variable is higher than $ranEvery. If it is, we then can execute the cron job. */
			if ($timeSince > $timeInterval)
			{
				/* Update the "lastran" column. */
				
				/* Prepare the query. */
				$data = Array
				(
					'lastran' => time()
				);
				
				/* Execute the query. */
				$check = $db->update('cronjobs', $data);
				
				/* Check if the cron job was updated successfully. If not, we should just skip. */
				if ($check)
				{
					/* Decode the options JSON. */
					$options = json_decode($cron['options'], true);
					
					/* Execute the cron job. */
					$M['crons']->executeCronJob($cron['cron'], $options);
					
					$i++;
				}
			}
			
		}
	}
	
	echo $i . ' crons ran.';
	
?>