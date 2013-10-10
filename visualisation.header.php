<?php session_start();
	
	include_once('../config.php');
	include_once('config.php');
	include_once('class/tools.php');
	include_once('class/entry.class.php');
	include_once('class/ressource.class.php');
	include_once('class/log.class.php');
	include_once('class/link.class.php');
	include_once('class/user.class.php');
	include_once('class/manager.class.php');
	
	$manager = new Manager(getConnection());
	
	
	
?>
<!doctype html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<!-- 
		<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=0.5, user-scalable=0' name='viewport' />
		<meta name="viewport" content="width=device-width" />
		 -->
		<title>Encyclopétrie</title>
		
		<link rel="stylesheet" href="css/visualisation.css" type="text/css">
		<link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.css" rel="stylesheet">
		
		<script src="js/jquery.js"></script>
		<script src="js/jquery.mobile.custom.min.js"></script>
		<script src="js/jquery-ui-1.10.3.custom.min.js"></script>
		<script src="js/html5slider.js"></script>
	</head>
	<body>
