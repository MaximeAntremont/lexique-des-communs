<?php 
	
	session_start();
	
	include_once('../config.php');
	include_once('config.php');
	include_once('class/tools.php');
	include_once('class/entry.class.php');
	include_once('class/ressource.class.php');
	include_once('class/log.class.php');
	include_once('class/link.class.php');
	include_once('class/user.class.php');
	include_once('class/manager.class.php');
		
	$manager = new Manager( getConnection() );
	
	$lexiques = $manager->getLexiquesAll();
	
	?>
	
<!doctype html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Sélection d'un lexique</title>
		<link rel="stylesheet" href="admin/css/admin.css" type="text/css">
	</head>
	<body>
	
	<div id="left_panel" >
		<div id="header" class="on">
			<h1>SELECTION</h1>
			<h3>d'un Lexique</h3>
		</div>
		
		<div id="content">
			<?php 
			if(!isConnected()){
				echo '<a href="admin/login.php">';
				echo '<div class="listSelector">';
				echo '<h3>Se connecter</h3>';
				echo '</div>';
				echo '</a>';
			}else{
				echo '<a href="admin/dashboard.php">';
				echo '<div class="listSelector">';
				echo '<h3>Accéder au Dashboard</h3>';
				echo '</div>';
				echo '</a>';
				echo '<a href="admin/logout.php">';
				echo '<div class="listSelector">';
				echo '<h3>Se déconnecter</h3>';
				echo '</div>';
				echo '</a>';
			}
			?>
		</div>
		
	</div>
	
	
	
	
	
	<div id="middle_panel" >		
		<div id="content">
	<?php
	if(count($lexiques) == 0){
		
		$lexique  = '<div class="list">';
		$lexique .= '<h3>Aucun lexique</h3>';
		$lexique .= '</div>';
		
		echo $lexique;
		
	}else
		foreach($lexiques as $lexique){
			
			if($lexique['statut'] == 0 && isConnected() && isSUDO()){
				echo '<a href="lexique.php?id='. $lexique['id'] .'">';
				echo '<div class="listSelector" >';
				echo '<h3>'. $lexique['name'] .'</h3>';
				echo '</div></a>';
				
			}elseif($lexique['statut'] == 1){
				echo '<a href="lexique.php?id='. $lexique['id'] .'">';
				echo '<div class="listSelector" >';
				echo '<h3>'. $lexique['name'] .'</h3>';
				echo '</div></a>';
			}
		
		}
	?>
		</div>
	</div>
	<div id="right_panel" >	
		<div id="content">
		</div>
	</div>
	</body>
</html>