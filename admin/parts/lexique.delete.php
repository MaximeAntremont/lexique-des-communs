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
	
if(!empty($_POST['lexique_id']) && isConnected() && isSUDO()){
	
	$id = htmlspecialchars($_POST['lexique_id']);
	$db = getConnection();
	$manager = new Manager( $db );
	$lexique = $manager->getLexiquesBy_id( $id );
	
	if(is_array($lexique)){
		
		if($db->query('DROP TABLE '. $lexique['attr'] .'category')
		&& $db->query('DROP TABLE '. $lexique['attr'] .'entry')
		&& $db->query('DROP TABLE '. $lexique['attr'] .'ressource')
		&& $db->query('DROP TABLE '. $lexique['attr'] .'link')
		&& $db->query('DROP TABLE '. $lexique['attr'] .'log')
		&& $manager->deleteLexique( $lexique['id'] )){
			
			echo '<div class="list"><h3>Et voilà, c\'est fait !</h3></div>';
			
		}else echo '<div class="list"><h3>Une erreur quelque part ! Il va y avoir des erreurs... :S</h3></div>';
		
	}echo '<div class="list"><h3>Ah, le lexique n\'a pas été trouvé...</h3></div>';
	

}else echo '<div class="list"><h3>Il semble qu\'il y ait un petit problème. Le lexique n\'a donc pas pu être supprimé !</h3><div>';