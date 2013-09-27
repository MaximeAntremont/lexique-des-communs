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
	
	if(!$manager->isHS() && !empty($_POST['log_val']) && is_string($_POST['log_val']) ){
		
		foreach($_POST as $key => $val){
			$_POST[$key] = htmlspecialchars(trim($val));
		}
		
		
		$log = new Log($_POST);
		
		$log->type(101);
		$log->ip( $_SERVER['REMOTE_ADDR'] );
		
		if($return = $manager->sendNewLog( $log ))
			echo json_encode(array('return'=> true, 'obj' => $log->entry_id()));
		else
			echo json_encode(array('return' => false));
		
	}else
		echo json_encode(array('return' => false));