<html>
<head>
<title>Nova Labs Open/Closed Widget - Status Check</title>
<link rel="stylesheet" href="css/bootstrap.min.css" />
</head>
<body>
<?php
	require_once('MDB2.php');
	include('config/config.php');

	/* FUNCTIONS =============================================================== */
	function queryErrorCheckNoDie($a)
	{
		if(PEAR::isError($a)) echo $a->getMessage();
	}

	function showDoorStatus($config,$mdbHandle)
	{
		$query = "SELECT doorValue,timestamp FROM {$config['targetTable']} ORDER BY timestamp DESC LIMIT 1;";
		$queryResult = $mdbHandle->queryAll($query);
		$error = queryErrorCheckNoDie($mdbHandle); if($error != '') $errors[] = $error;

		if($queryResult[0]['doorvalue'] == '1')
		{
			echo "<span style='width: 60px; display: block; margin-left: auto; margin-right: auto;' class='btn btn-success btn-large'>OPEN</span>";
		}
		
		else
		{
			echo "<span style='width: 60px; display: block; margin-left: auto; margin-right: auto;' class='btn btn-danger btn-large'>CLOSED</span>";
		}
		echo "<br />";
		echo "<p style='text-align:center;'>".date('M d \a\t H:i:s',strtotime($queryResult[0]['timestamp']))."</p>";
		return true;
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
	//	header('Content-Type: text/plain; charset=utf-8');
	showDoorStatus($config,$mdb2);
?>
</body>
</html>