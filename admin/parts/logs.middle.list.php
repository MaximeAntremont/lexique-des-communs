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

	
	if( isset($_POST['attr']) && isConnected() && (isMODO() || isSUDO()) ){
		
		$manager = new Manager(getConnection(), htmlspecialchars($_POST['attr']));
		
		$logs = $manager->getLogBy_type(101);
		
		$logL = count($logs);
		
		if($logL == 0){
			
			$listSelector = '<div class="list">';
			$listSelector .= '<h3>Vide</h3>';
			$listSelector .= '</div>';
			
			echo $listSelector;
			
		}
		
		foreach($logs as $log){
		
			$listSelector = '<div todo="printLog" log_id="'. $log->id() .'" class="listSelector">';
			$listSelector .= '<h3>Rapport n° '. $logL .'</h3>';
			$listSelector .= '</div>';
			
			echo $listSelector;
			$logL--;
		}
		
	}else{
		
		$listSelector = '<div class="list">';
		$listSelector .= '<h3>Erreur</h3>';
		$listSelector .= '</div>';
		
		echo $listSelector;
		
	}