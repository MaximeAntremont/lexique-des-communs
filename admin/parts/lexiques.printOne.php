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

	
	if(!empty($_POST['attr']) && isset($_SESSION['user_token']) && $_SESSION['user_token'] < $timestamp){
		
		$manager = new Manager(getConnection(), htmlspecialchars($_POST['attr']));
		
		$listSelector = '<div class="list">';
		$listSelector .= '<h3>'. $manager->getEntrysLength() .' entrée(s)</h3>';
		$listSelector .= '</div>';
		echo $listSelector;
		
		$listSelector = '<div class="list">';
		$listSelector .= '<h3>'. $manager->getRessourcesLength() .' ressource(s)</h3>';
		$listSelector .= '</div>';
		echo $listSelector;
		
		$listSelector = '<div class="list" class="listSelector">';
		$listSelector .= '<h3>Désactiver ce lexique</h3>';
		$listSelector .= '</div>';
		echo $listSelector;
		
		$listSelector = '<div class="list">';
		$listSelector .= '<h3>Supprimer ce lexique</h3>';
		$listSelector .= '</div>';
		echo $listSelector;
		
	}
	
?>