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
	
	if(isset($_SESSION['lexique_attr']) ){
		
		$manager = new Manager(getConnection(), $_SESSION['lexique_attr']);
		
		$entries = $manager -> getEntryAll('entry_id, entry_val');
		$index = array();
		
		for($letter = 'A'; $letter <= 'Z'; $letter++){
			
			if($letter == 'AA') break;
			
			$selected = null;
			
			foreach($entries as $entry){
				
				$val = $entry->val();
				if(strtoupper($val[0]) === $letter){
					$selected[] = array('id' => $entry->id(), 'val' => stripcslashes($entry->val()) );
				}
				
			}
			
			$index[] = array('char' => $letter, 'select' => $selected);
			
		}
		
		echo json_encode($index);
		
	}else{
		
		echo "erreur";
		
	}