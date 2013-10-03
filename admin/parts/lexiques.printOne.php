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

	
	if(!empty($_POST['lexique_id']) && isConnected()){
		
		$id = htmlspecialchars($_POST['lexique_id']);
		$manager = new Manager( getConnection() );
		
		if($lexique = $manager->getLexiquesBy_id( $id )){
		
			$managerLexique = new Manager(getConnection(), $lexique['attr']);
			
			$listSelector = '<div class="list">';
			$listSelector .= '<h3>'. $lexique['name'] .'</h3>';
			$listSelector .= '</div>';
			echo $listSelector;
			
			$listSelector = '<div class="list">';
			$listSelector .= '<h3>'. $managerLexique->getEntrysLength() .' entrée(s)</h3>';
			$listSelector .= '</div>';
			echo $listSelector;
			
			$listSelector = '<div class="list">';
			$listSelector .= '<h3>'. $managerLexique->getRessourcesLength() .' ressource(s)</h3>';
			$listSelector .= '</div>';
			echo $listSelector;
			
			$listSelector = '<a href="../lexique.php?id='. $lexique['id'] .'" target=_BLANK >';
			$listSelector .= '<div class="list">';
			$listSelector .= '<h3>Ouvrir le lexique</h3>';
			$listSelector .= '</div>';
			$listSelector .= '</a>';
			echo $listSelector;
			
			if(isSUDO()){
				// $listSelector = '<div class="listSelector">';
				// $listSelector .= '<h3>Modifier l\'attribut</h3>';
				// $listSelector .= '</div>';
				// echo $listSelector;
				
				$listSelector = '<div todo="printRessources" class="listSelector">';
				$listSelector .= '<h3>Gérer les ressources</h3>';
				$listSelector .= '</div>';
				echo $listSelector;
				
				$listSelector = '<div todo="toggleActivity" class="listSelector">';
				$listSelector .= '<h3>'. (($lexique['statut'] == 1) ? 'Désactiver' : 'Acitver') .' ce lexique</h3>';
				$listSelector .= '</div>';
				echo $listSelector;
				
				$listSelector = '<div todo="deleteLexique" class="listSelector">';
				$listSelector .= '<h3>Supprimer ce lexique</h3>';
				$listSelector .= '</div>';
				echo $listSelector;
			}
			
		}else echo '<div class="list"><h3>Erreur (étape 2)</h3></div>';
	
	}else echo '<div class="list"><h3>Erreur (étape 1)</h3></div>';
	
?>