<?php
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

	/* MAIN ==================================================================== */
	header('Content-Type: text/plain; charset=utf-8');

	if(isSet($_GET['temp']) > 0)
	{
		// get values
		$thermostatValues = array();
		foreach($_GET as $key=>$value)
		{
			$thermostatValues[$key] = $value;
		}

		// author
		if(isSet($_GET['author']))
		{
			$authorStr = $_GET['author'];
			unset($thermostatValues['author']);  // prevents double print at insert query
		} else $authorStr = 'unknown';
		
		// generate timestamp
		date_default_timezone_set('America/New_York');
		$timestamp = date('Y-m-d H:i:s', time());
		
		// generate insert string
		$insertKeys = "(`author`,`timestamp`,`".implode(array_keys($thermostatValues),'`,`')."`)";
		$insertValues = "('{$authorStr}', '{$timestamp}', ".implode($thermostatValues,',').")";

		// insert query
		$insertQuery = "INSERT INTO {$config['tableThermostatEvents']} {$insertKeys} VALUES {$insertValues}";
		$affected =& $mdb2->exec($insertQuery);
		$error = queryErrorCheckNoDie($mdb2); if($error != '') $errors[] = $error;

	} else {
		echo "Thermostat status is not recognized.";
	}
?>