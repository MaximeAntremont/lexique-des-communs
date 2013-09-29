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
	
	if(isset($_GET['id'])){
		
		$id = htmlspecialchars($_GET['id']);
		$manager = new Manager( getConnection() );
		
		if( $lexique = $manager->getLexiquesBy_id($id) ){
			
			if($lexique['statut'] == 0 && isConnected() && isSUDO()){
				
				$_SESSION['lexique_attr'] = $lexique['attr'];
				header('Location:visualisation.php');
				
			}else if($lexique['statut'] == 1){
				
				$_SESSION['lexique_attr'] = $lexique['attr'];
				header('Location:visualisation.php');
				
			}else{
				echo 'Une erreur est survenue';
			}
			
		}else echo 'Ce lexique n \'est pas enregistré';
		
	}else echo "rien d'indiqué";