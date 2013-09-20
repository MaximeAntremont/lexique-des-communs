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
	
	if( !$manager->isHS() ){
		
		foreach($_POST as $key => $val){
			$_POST[$key] = htmlspecialchars(trim($val));
		}
		
		$link = new Link($_POST);
		
		echo json_encode(array('return'=> $manager->sendNewLink( $link )));
		
	}else
		echo json_encode(array('return' => false));