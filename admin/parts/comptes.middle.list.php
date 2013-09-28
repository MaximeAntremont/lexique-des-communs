<?php session_start();

	$timestamp = time();
	
	include_once('../../../config.php');
	include_once('../../config.php');
	include_once('../../class/tools.php');
	include_once('../../class/entry.class.php');
	include_once('../../class/ressource.class.php');
	include_once('../../class/log.class.php');
	include_once('../../class/link.class.php');
	include_once('../../class/user.class.php');
	include_once('../../class/manager.class.php');

	
	if(isset($_SESSION['user_token']) && $_SESSION['user_token'] < $timestamp){
		
		$manager = new Manager(getConnection(), "lexique_admin_");
		
		$users = $manager->getUserAll();
		
		foreach($users as $user){
			$listSelector = '<div user_id="'. $user->id() .'" class="listSelector">';
			$listSelector .= '<h3>'. $user->name() .'</h3>';
			$listSelector .= '</div>';
			echo $listSelector;
		}
		
	}