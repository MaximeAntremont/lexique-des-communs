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
		
		<table border=1 style="font-size: 12px;table-layout: fixed;width:900px;">
			<?php
			
				$logs = $manager->getLogBy_type(101);
				
				foreach($logs as $log){
					
					echo '<tr style="padding-bottom: 20px;" >
							<th>'. $log->id() .'</th>
							<th>'. $log->create_date() .'</th>
							<th style="max-width: 600px;" >'. $log->val() .'</th>
						</tr>';
					
				}
				
			?>
		</table>
		
	</body>
</html>