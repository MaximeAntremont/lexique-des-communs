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

	
	if(!empty($_POST['lexique_id']) && isConnected() && isSUDO()){
		
		$id = htmlspecialchars($_POST['lexique_id']);
		$manager = new Manager(getConnection());
		
		echo ($manager->lexiqueToggleActivity( $_POST['lexique_id'] )) ? '<div class="list"><h3>Modification: Succès</h3></div>' : '<div class="list"><h3>Modification: Echec</h3></div>';
		
	}else echo '<div class="list"><h3>Erreur</h3></div>';
	
?>