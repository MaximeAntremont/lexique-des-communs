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
	
	if(isset($_SESSION['lexique_id']) ){
		
		foreach($_POST as $key => $val){
			$_POST[$key] = htmlspecialchars(trim($val));
		}
		// print_r($_SERVER);
		echo json_encode(array('url' => "http://".$_SERVER['HTTP_HOST'].str_replace('utils/getURL.util.php', 'lexique.php', $_SERVER['REQUEST_URI'])."?id=".$_SESSION['lexique_id']. ( (!empty($_POST['entry_id']) ? "&en=".$_POST['entry_id'] : "") )));
				
	}else
		echo json_encode(array('return' => false));