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
	$query = "SELECT timestamp,temp,fstate FROM {$config['tableThermostatEvents']} ORDER BY timestamp ASC;";
	$queryResult = $mdb2->queryAll($query);
	$error = queryErrorCheckNoDie($mdb2); if($error != '') $errors[] = $error;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<title>Nova Labs Thermostat Reporting</title>
	<script type="text/javascript" src="http://www.google.com/jsapi"></script>
	<script type="text/javascript">
	  google.load('visualization', '1', {packages: ['corechart']});
	</script>
	<script type="text/javascript">
	  function drawVisualization() {
		// Create and populate the data table.
		var data = google.visualization.arrayToDataTable([
		<?php
			echo "['Timestamp','Temperature','Fan State'],";
			foreach($queryResult as $key=>$row)
			{
				if($row['temp'] == -1) continue;
				$timestamp = $row['timestamp'];
				$temp = $row['temp'];
				$fstate = ($row['fstate']==1?74:73.5);
				echo "['{$timestamp}',{$temp},{$fstate}],";
			}
		?>
		  ]);
	  
		// Create and draw the visualization.
		var ac = new google.visualization.ComboChart(document.getElementById('visualization'));
		ac.draw(data, {
		  title : 'Nova Labs Thermostat Data',
		  width: 800,
		  height: 600,
		  vAxis: {title: "Temp (Deg. F)"},
		  hAxis: {title: "Time"},
		  seriesType: "area",
		  series: {1: {type: "line"}}
		  //vAxis.minValue: 73,
		  //vAxis.maxValue: 79,
		});
	  }
	  

	  google.setOnLoadCallback(drawVisualization);
	</script>
  </head>
  <body style="font-family: Arial;border: 0 none;">
	<div id="visualization" style="width: 800px; height: 600px;"></div>
  </body>
</html>