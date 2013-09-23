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
	// $_POST['entry_val'] = "ma";
	
	if(!$manager->isHS() && !empty($_POST['ress_entry_id']) && is_numeric($_POST['ress_entry_id']) && !empty($_POST['ress_val']) && is_string($_POST['ress_val']) ){
		
		foreach($_POST as $key => $val){
			$_POST[$key] = htmlspecialchars(trim($val));
		}
		
		
		$ress = new Ressource($_POST);
		
		foreach($ress_dico as $key => $dico){
			if(isset($dico['regex']))
				if( preg_match($dico['regex'], $ress->val()) ) $ress->type( $key );
		}
		
		if($return = $manager->sendNewRessource( $ress ))
			echo json_encode(array('return'=> true));
		else
			echo json_encode(array('return' => false, 'obj' => $ress->getArray()));
		
	}else
		echo json_encode(array('return' => false));