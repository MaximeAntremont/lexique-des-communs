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

	
	if( isset($_POST['lexique_id']) && isConnected() && (isMODO() || isSUDO()) ){
		
		$id = htmlspecialchars($_POST['lexique_id']);
		$manager = new Manager(getConnection());
		$lexique = $manager->getLexiquesBy_id( $id );
		
		if(is_array($lexique)){
			
			$managerLexique = new Manager(getConnection(), $lexique['attr']);
			$logs = $managerLexique->getLogBy_type(101);
			$logL = count($logs);
		
			if($logL == 0){
				
				$listSelector = '<div class="list">';
				$listSelector .= '<h3>Aucun rapport de bug, youhou !</h3>';
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
			
		}else echo '<div class="list"><h3>Hein ? Ce lexique n\'existe pas ?</h3></div>';
		
		
		
		
		
		
	}else echo '<div class="list"><h3>Ciel ! Une erreur !</h3></div>';