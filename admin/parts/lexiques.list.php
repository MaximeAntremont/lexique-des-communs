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
	
if(isConnected()){

	$manager = new Manager( getConnection() );
	
	$lexiques = $manager->getLexiquesAll();
	
	if(count($lexiques) == 0){
		
		$lexique  = '<div class="list">';
		$lexique .= '<h3>Aucun lexique</h3>';
		$lexique .= '</div>';
		
		echo $lexique;
		
	}else
		foreach($lexiques as $lexique){
			
			if($lexique['statut'] == 0 && isSUDO()){
				echo '<div todo="printLexique" lexique_id="'. $lexique['id'] .'" class="listSelector" >';
				echo '<h3>'. $lexique['name'] .'</h3>';
				echo '</div>';
				
			}elseif($lexique['statut'] == 1){
				echo '<div todo="printLexique" lexique_id="'. $lexique['id'] .'" class="listSelector" >';
				echo '<h3>'. $lexique['name'] .'</h3>';
				echo '</div>';
			}
		
		}

}