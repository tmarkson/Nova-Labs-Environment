<?php
	require_once('twitteroauth/TwitterOAuth.php');
	require_once('MDB2.php');
	include('config/config.php');
 
	/* DEFINITIONS ============================================================= */

	/* FUNCTIONS =============================================================== */
	function queryErrorCheckNoDie($a)
	{
		if(PEAR::isError($a)) echo $a->getMessage();
	}

	/* CONNECTION ============================================================== */
	$mdb2 =& MDB2::connect("mysql://".$config['user'].":".$config['pass']."@".$config['host']."/".$config['database']);
	$error = queryErrorCheckNoDie($mdb2); if($error != '') $errors[] = $error;

	/* set MDB2 fetch mode
	  MDB2_FETCHMODE_ORDERED (with numeric array keys)
	  MDB2_FETCHMODE_ASSOC (with text array keys)
	*/
	$mdb2->setFetchMode(MDB2_FETCHMODE_ASSOC);

	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_SECRET);


	/* MAIN ==================================================================== */
	header('Content-Type: text/plain; charset=utf-8');

	if(isSet($_REQUEST['switch']))
	{
		// if switch value is valid, log and tweet the event
		if($_REQUEST['switch'] == '0' || $_REQUEST['switch'] == '1')
		{
			// author
			if(isSet($_REQUEST['author'])) $authorStr = $_REQUEST['author']; else $authorStr = 'unknown';
			
			// generate timestamp
			date_default_timezone_set('America/New_York');
			$timestamp = date('Y-m-d H:i:s', time());

			$insertQuery = "INSERT INTO {$config['tableDoorEvents']} (doorValue,tempValue,author,timestamp) VALUES ({$_REQUEST['switch']},0.0,'{$authorStr}','{$timestamp}')";
			$affected =& $mdb2->exec($insertQuery);
			$error = queryErrorCheckNoDie($mdb2); if($error != '') $errors[] = $error;

			// TWEET TWEET
			// if twitter connection successful, create the tweet text and update twitter status
			if($connection->get('account/verify_credentials'))
			{
				$statusStr = 'The door to @nova_labs was '.($_REQUEST['switch']?'Opened':'Closed').' on '.date('M d \a\t H:i:s',(time()+0)).' by '.ucwords(strtolower($authorStr));
				$connection->post('statuses/update', array('status' => $statusStr));
			}
			echo "Event logged and tweeted";
		} else {
			echo "Status is not recognized.";
		}
	}
	
	else
	{
		echo "No status passed.";
	}
?>