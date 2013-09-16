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
	
	if(!$manager->isHS() && !empty($_POST['entry_id']) && is_string($_POST['entry_id'])){
		
		
		$entry_id = htmlspecialchars($_POST['entry_id']);
		
		if($entry = $manager->getEntryBy_id( $entry_id )){
			
			
			echo json_encode($entry->getArray());
			
		}else{
			
			echo json_encode( array('error' => false) );
			
		}
		
		
	}