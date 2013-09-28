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

	
	if(!empty($_POST['attr']) && isConnected() && isSUDO()){
		
		$attr = htmlspecialchars($_POST['attr']);
		$manager = new Manager(getConnection(), $attr);
		$lines = file('../bdd_lexiques.txt', FILE_SKIP_EMPTY_LINES);
		$lex = array();
		
		foreach($lines as $line){
			
			if(preg_match('#"'. preg_quote($attr) .'"#', $line, $out)){
				
				$lex['name'] = preg_replace('#"'. preg_quote($attr) .'"#', "", $line);
				$lex['active'] = ($lex['name'][0] == ";") ? false : true ;
				break;
			}
			
		}
		
		
		$listSelector = '<div class="list">';
		$listSelector .= '<h3>'. $manager->getEntrysLength() .' entrée(s)</h3>';
		$listSelector .= '</div>';
		echo $listSelector;
		
		$listSelector = '<div class="list">';
		$listSelector .= '<h3>'. $manager->getRessourcesLength() .' ressource(s)</h3>';
		$listSelector .= '</div>';
		echo $listSelector;
		
		$listSelector = '<a href="../lexique.php?l='. $attr .'" target=_BLANK >';
		$listSelector .= '<div class="list">';
		$listSelector .= '<h3>Ouvrir le lexique</h3>';
		$listSelector .= '</div>';
		$listSelector .= '</a>';
		echo $listSelector;
		
		$listSelector = '<div class="listSelector">';
		$listSelector .= '<h3>Modifier l\'attribut</h3>';
		$listSelector .= '</div>';
		echo $listSelector;
		
		$listSelector = '<div todo="toggleActivity" class="listSelector">';
		$listSelector .= '<h3>'. (($lex['active']) ? 'Désactiver' : 'Acitver') .' ce lexique</h3>';
		$listSelector .= '</div>';
		echo $listSelector;
		
		$listSelector = '<div todo="deleteLexique" class="listSelector">';
		$listSelector .= '<h3>Supprimer ce lexique</h3>';
		$listSelector .= '</div>';
		echo $listSelector;
		
	}
	
?>