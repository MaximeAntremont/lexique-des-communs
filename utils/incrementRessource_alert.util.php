<?php
	
	session_start();
	
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
	
	// $manager = new Manager(getConnection());
	
	if(!empty($_POST['ress_id']) && is_string($_POST['ress_id']) && isset($_SESSION['lexique_attr']) ){
		
		$manager = new Manager(getConnection(), $_SESSION['lexique_attr']);
		
		$ress_id = htmlspecialchars($_POST['ress_id']);
		
		$ress = $manager->getRessourceBy_id($ress_id);
		$ress->alert( $ress->alert()+1 );
		
		if($ress instanceof Ressource && !$manager->isRessourceAlerted($ress) && ($manager->updateRessource( $ress )) ){
			
			$log = new Log();
			$log->type(303);
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