<?php session_start();
	
	include_once('../../../config.php');
	include_once('../../config.php');
	include_once('../../class/tools.php');
	include_once('../../class/entry.class.php');
	include_once('../../class/ressource.class.php');
	include_once('../../class/log.class.php');
	include_once('../../class/link.class.php');
	include_once('../../class/user.class.php');
	include_once('../../class/manager.class.php');
	
	if(isConnected() && (isSUDO() || isMODO())){
		
		$manager = new Manager( getConnection() );
		
		$lexiques = $manager->getLexiquesAll();
		
		echo '<div todo="dashboard" class="listSelector">';
		echo '<h3>Retour</h3>';
		echo '</div>';
		
		if( is_array($lexiques) ){
			
			foreach($lexiques as $lexique){
				
				echo '<div todo="printLogs" lexique_id="'. $lexique['id'] .'" class="listSelector">';
				echo '<h3>'. $lexique['name'] .'</h3>';
				echo '</div>';
				
			}
			
		}else echo '<div class="list"><h3>Aucun lexique</h3></div>';

	}else echo '<div class="list"><h3>Une erreur sauvage vous attaque !</h3></div>';