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
		
		$typeId = $ress->type();
		$titre = $ress->titre();
		
		echo '<div class="list">';
		echo '<h3>id: '. $ress->id() .'</h3>';
		echo '</div>';
		
		echo '<div class="list">';
		if(!empty($titre)){
			
			echo '<h3>['.$ress->titre().']</h3>';
			
		}else if(isset($ress_dico[$typeId])){
			
			$type = $ress_dico[$typeId];
			
			if($typeId == 950) echo '<h3>'.$ress->val().'</h3>';
			else if($typeId == 990) echo '<h3>'.$ress->val().'</h3>';
			else echo '<h3>[Erreur]</h3>';
		
		}
		echo '</div>';
		
		echo '<div todo="deleteRessource" ress_id="'.$ress->id().'" class="listSelector danger">';
		echo '<h3>Supprimer la ressource</h3>';
		echo '</div>';
		
	}else{
		echo '<div class="list">';
		echo '<h3>Erreur</h3>';
		echo '<div>';
	}