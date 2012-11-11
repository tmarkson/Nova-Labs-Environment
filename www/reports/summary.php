<?php
	require_once('MDB2.php');
	include('../config/config.php');

	/* FUNCTIONS =============================================================== */
	function queryErrorCheckNoDie($a)
	{
		if(PEAR::isError($a)) echo $a->getMessage();
	}

	/* CONNECTION ============================================================== */
	$mdb2 =& MDB2::connect("mysql://".$config['user'].":".$config['pass']."@".$config['host']."/".$config['database']);
	$error = queryErrorCheckNoDie($mdb2); if($error != '') $errors[] = $error;

	/* set fetch mode
	  MDB2_FETCHMODE_ORDERED (with numeric array keys)
	  MDB2_FETCHMODE_ASSOC (with text array keys)
	*/
	$mdb2->setFetchMode(MDB2_FETCHMODE_ASSOC);

	/* MAIN ==================================================================== */
	// select avg(temp) as mean from novalabsThermostatEvents WHERE timestamp BETWEEN '2012-10-28' AND '2012-10-30';
	// select count(fstate) as mode from novalabsThermostatEvents WHERE timestamp BETWEEN '2012-10-29' AND '2012-10-30' AND fstate=1;
	$query = "SELECT timestamp,temp,fstate,t_heat,t_cool FROM {$config['tableThermostatEvents']} ORDER BY timestamp DESC LIMIT 2880;";
	$queryResult = $mdb2->queryAll($query);
	$error = queryErrorCheckNoDie($mdb2); if($error != '') $errors[] = $error;
	array_multisort($queryResult, SORT_ASC);
	?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<title>Nova Labs Thermostat Reporting Graph</title>
  </head>
  <body style="font-family: Arial;border: 0 none;">
	<p>This page shows summary reporting for historical data from the thermostat at Nova Labs.</p>
  </body>
</html>