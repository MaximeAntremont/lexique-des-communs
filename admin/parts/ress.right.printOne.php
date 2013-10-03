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

	
	if(isset($_POST['lexique_id']) && $_POST['ress_id'] && isConnected() && (isSUDO() || isMODO()) ){
		
		$manager = new Manager(getConnection());
		$lexique = $manager->getLexiquesBy_id( htmlspecialchars( $_POST['lexique_id'] ) );
		$manager = new Manager(getConnection(), $lexique['attr']);
		$ress = $manager->getRessourceBy_id( htmlspecialchars( $_POST['ress_id'] ) );
		
		echo '<div todo="printRessource" ress_id="'. $ress->id() .'" class="listSelector">';
		echo '<h3>'.( ($ress->type() == 990) ? ( ($ress->titre() != '') ? $ress->titre() : "LONG texte" ) : ( ($ress->titre() != '') ? $ress->titre() : "autre") ).'</h3>';
		echo '</div>';
		
	}else{
		echo '<div class="list">';
		echo '<h3>Erreur</h3>';
		echo '<div>';
	}