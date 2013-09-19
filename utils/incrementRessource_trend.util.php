<?php
	
	include_once('../../config.php');
	include_once('../config.php');
	include_once('../class/tools.php');
	include_once('../class/entry.class.php');
	include_once('../class/ressource.class.php');
	include_once('../class/log.class.php');
	include_once('../class/link.class.php');
	include_once('../class/user.class.php');
	include_once('../class/category.class.php');
	include_once('../class/manager.class.php');
	
	header('Content-type: application/json');
	
	$manager = new Manager(getConnection());
	
	if(!$manager->isHS() && !empty($_POST['ress_id']) && is_string($_POST['ress_id'])){
		
		
		$ress_id = htmlspecialchars($_POST['ress_id']);
		
		$ress = $manager->getRessourceBy_id($ress_id);
		$ress->trend( $ress->trend()+1 );
		
		if($ress instanceof Ressource && $manager->getLastTrendChange($ress) != 301 && ($manager->updateRessource( $ress )) ){
			
			$log = new Log();
			$log->type(301);
			$log->ip($_SERVER['REMOTE_ADDR']);
			$log->val("id$".$ress->id());
			$log->entry_id( $ress->entry_id() );
			$manager->sendNewLog($log);
			
			echo json_encode(array('return' => true));
			
		}else{
			
			echo json_encode( array('return' => false) );
			
		}
		
		
	}else{
		
		echo json_encode( array('return' => false) );
		
	}