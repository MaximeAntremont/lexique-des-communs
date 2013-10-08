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

	
	if(isset($_POST['lexique_id']) && isConnected() && (isSUDO() || isMODO()) ){
		
		$manager = new Manager(getConnection());
		$lexique = $manager->getLexiquesBy_id( htmlspecialchars( $_POST['lexique_id'] ) );
		$manager = new Manager(getConnection(), $lexique['attr']);
		$ressources = $manager->getRessourceAll('ress_id, ress_val, ress_titre, ress_type');
		
		foreach($ressources as $ress){
		
			echo '<div todo="printRessource" ress_id="'. $ress->id() .'" class="listSelector">';
			$typeId = $ress->type();
			$titre = $ress->titre();
			if(!empty($titre)){
				
				echo '<h3>['.$ress->titre().']</h3>';
				
			}else if(isset($ress_dico[$typeId])){
				
				$type = $ress_dico[$typeId];
				
				if($typeId == 950) echo '<h3>'.$ress->val().'</h3>';
				else if($typeId == 990) echo '<h3>'.substr($ress->val(), 0, 17).'...</h3>';
				else echo '<h3>[Erreur]</h3>';
				
			}
			
			echo '</div>';
		}
		
	}else{
		echo '<div class="list">';
		echo '<h3>Erreur</h3>';
		echo '<div>';
	}