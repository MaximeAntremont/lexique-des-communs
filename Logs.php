<?php
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
		<title>Encyclopétrie - Logs</title>
	</head>
	<body>
		
		<table style="font-size: 12px;table-layout: fixed;width:600px;">
			<?php
			
				$logs = $manager->getLogBy_type(101);
				
				foreach($logs as $log){
					
					echo '<tr>
							<th>'. $log->id() .'</th>
							<th>'. $log->type() .'</th>
							<th style="max-width: 300px;" >'. $log->val() .'</th>
							<th>'. $log->id() .'</th>
						</tr>';
					
				}
				
			?>
		</table>
		
	</body>
</html>