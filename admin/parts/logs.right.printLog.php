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

	
	if( isset($_POST['log_id']) && isset($_POST['attr']) && isConnected() && (isMODO() || isSUDO()) ){
		
		$manager = new Manager(getConnection(), htmlspecialchars($_POST['attr']));
		
		$log = $manager->getLogBy_id(htmlspecialchars($_POST['log_id']));
		
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
		
	}else{
		
		$listSelector = '<div class="list">';
		$listSelector .= '<h3>Erreur</h3>';
		$listSelector .= '</div>';
		
		echo $listSelector;
		
	}