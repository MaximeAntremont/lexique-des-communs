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
	
	if(isset($_SESSION['lexique_attr'])){
		
		$manager = new Manager(getConnection(), $_SESSION['lexique_attr']);
		
		$entries = $manager -> getEntryAll('entry_id, entry_val', 'ORDER BY entry_val ASC');
		
		foreach($entries as $e){
			
			$results[] = $e->getArray();
			
		}
		
		echo json_encode($results);
		
	}else{
		
		echo "erreur";
		
	}