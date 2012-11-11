<?php
	/*
		MySql variables for MDB2 connections
	*/
	$config['host'] = "";
	$config['user'] = "";
	$config['pass'] = "";
	$config['database'] = "";
	$config['tableDoorEvents'] = "";
	$config['tableThermostatEvents'] = "";

	/*
		Twitter OAuth variables for @nova_labs_door

		1) Go to https://dev.twitter.com/apps
		2) Sign in to an account
		3) Add a new application, fill in the details
		4) On the application page, create an access token
		5) Under 'OAuth settings' section, change 'Access level' to 'Read and write'
		6) Copy and paste the respective variables below
	*/	
	define("CONSUMER_KEY", "");
	define("CONSUMER_SECRET", "");
	define("OAUTH_TOKEN", "");
	define("OAUTH_SECRET", "");

?>