<?php session_start();
	
	include_once('../../../config.php');
	include_once('../../config.php');
	include_once('../../class/tools.php');
	include_once('../../class/entry.class.php');
	include_once('../../class/ressource.class.php');
	include_once('../../class/log.class.php');
	include_once('../../class/link.class.php');
	include_once('../../class/user.class.php');
	include_once('../../class/manager.class.php');
	
if(!empty($_POST['lexique_id']) && !empty($_POST['entry_id']) && isConnected() && (isSUDO() || isMODO()) ){
	
	$manager = new Manager(getConnection());
	$lexique = $manager->getLexiquesBy_id( htmlspecialchars( $_POST['lexique_id'] ) );
	$manager = new Manager(getConnection(), $lexique['attr']);
	$entry_id = htmlspecialchars($_POST['entry_id']);
	
	if($manager->deleteEntryBy_id($entry_id) && $manager->deleteRessourceBy_entry_id( $entry_id )) echo '<div class="list"><h3>Succès total !</h3><div>';
	else echo '<div class="list"><h3>Échec total !</h3><div>';

}else echo '<div class="list"><h3>Il semble qu\'il y ait un petit problème. Le lexique n\'a donc pas pu être supprimé !</h3><div>';