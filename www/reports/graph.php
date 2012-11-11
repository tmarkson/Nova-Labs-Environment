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
	<script type="text/javascript" src="http://www.google.com/jsapi"></script>
	<script type="text/javascript">
	  google.load('visualization', '1', {packages: ['corechart']});
	</script>
	<script type="text/javascript">
	  function drawVisualization() {
		// Create and populate the data table.
		var data = google.visualization.arrayToDataTable([
		<?php
			echo "['Timestamp','Temperature','Fan State','Heat Target','Cool Target'],";
			foreach($queryResult as $key=>$row)
			{
				if($row['temp'] == -1) continue;
				$timestamp = $row['timestamp'];
				$temp = $row['temp'];
				$fstate = ($row['fstate']==1?74:73.5);
				$t_heat = ($row['t_heat']===NULL?60:$row['t_heat']);
				$t_cool = ($row['t_cool']===NULL?60:$row['t_cool']);
				echo "['{$timestamp}',{$temp},{$fstate},{$t_heat},{$t_cool}],";
			}
		?>]);
		// Create and draw the visualization.
		var ac = new google.visualization.ComboChart(document.getElementById('visualization'));
		ac.draw(data, {
		  title : 'Nova Labs Thermostat Data',
		  width: 800,
		  height: 600,
		  vAxis: {title: "Temp (Deg. F)"},
		  hAxis: {title: "Time"},
		  seriesType: "line",
		  series: {2: {type: "line"}}
		  //vAxis.minValue: 73,
		  //vAxis.maxValue: 79,
		  //vAxis.minorGridlines: {count: 20, color: #0F0},
		  //minorGridlines.count: 20
		});
	  }
	  

	  google.setOnLoadCallback(drawVisualization);
	</script>
  </head>
  <body style="font-family: Arial;border: 0 none;">
	<p>This page shows the last 2 days of historical data from the thermostat at Nova Labs.</p>
	<p><strong>Latest thermostat status:</strong> 
	<?php $latest = (end($queryResult)); ?>
	<ol>
	  <li><?php echo "Timestamp: {$latest['timestamp']}"; ?></li>
	  <li><?php echo "Temperature: {$latest['temp']}"; ?></li>
	  <li><?php echo "Fan State: ".($latest['fstate']?'On':'Off'); ?></li>
	  <li><?php echo "Heat Target: {$latest['t_heat']}"; ?></li>
	  <li><?php echo "Cool Target: {$latest['t_cool']}"; ?></li>
	</p>
	<div id="visualization" style="width: 800px; height: 600px;"></div>
  </body>
</html>