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

	
	if( isset($_POST['log_id']) && isset($_POST['lexique_id']) && isConnected() && (isMODO() || isSUDO()) ){
		
		$id = htmlspecialchars($_POST['lexique_id']);
		$manager = new Manager(getConnection());
		$lexique = $manager->getLexiquesBy_id( $id );
		
		if(is_array($lexique)){
			
			$managerLexique = new Manager(getConnection(), $lexique['attr']);
			$log = $managerLexique->getLogBy_id(htmlspecialchars($_POST['log_id']));
		
			if($log instanceof Log){
				
				$listSelector = '<div class="list">';
				$listSelector .= '<h3>'. $log->val() .'</h3>';
				$listSelector .= '</div>';
				
				$listSelector .= '<div class="list">';
				$listSelector .= '<h3>ID de l\'entrée: '. $log->entry_id() .'</h3>';
				$listSelector .= '</div>';
				
				echo $listSelector;
				
			}else{
				
				$listSelector = '<div class="list">';
				$listSelector .= '<h3>Vide</h3>';
				$listSelector .= '</div>';
				
				echo $listSelector;
				
			}
		}
		
		
		
	}else{
		
		$listSelector = '<div class="list">';
		$listSelector .= '<h3>Rhaaaa... Et voilà, une erreur !!!</h3>';
		$listSelector .= '</div>';
		
		echo $listSelector;
		
	}