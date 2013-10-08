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
	
if(!empty($_POST['lexique_id']) && !empty($_POST['entry_id']) && !empty($_POST['ress_id']) && isConnected() && (isSUDO() || isMODO()) ){
	
	$manager = new Manager(getConnection());
	$lexique = $manager->getLexiquesBy_id( htmlspecialchars( $_POST['lexique_id'] ) );
	$manager = new Manager(getConnection(), $lexique['attr']);
	$entry_id = htmlspecialchars($_POST['entry_id']);
	$ress_id = htmlspecialchars($_POST['ress_id']);
	
	if($manager->deleteRessourceBy_id( $ress_id ) && $manager->deleteLinkBy_ress_id( $ress_id )) echo '<div class="list"><h3>Soit, elle vient d\'être supprimée.</h3><div>';
	else echo '<div class="list"><h3>Lamentable... Ca n\'a pas bien fonctionné...</h3><div>';

}else echo '<div class="list"><h3>Il semble qu\'il y ait un petit problème. La ressource n\'a donc pas pu être supprimé !</h3><div>';