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
	
if(!empty($_POST['attr']) && isConnected() && isSUDO()){
	
	$attr = htmlspecialchars($_POST['attr']);
	$db = getConnection();
	$newFile = array();
	$lines = file('../bdd_lexiques.txt', FILE_SKIP_EMPTY_LINES);
	
	if($db->query('DROP TABLE '. $attr .'category')
	&& $db->query('DROP TABLE '. $attr .'entry')
	&& $db->query('DROP TABLE '. $attr .'ressource')
	&& $db->query('DROP TABLE '. $attr .'link')
	&& $db->query('DROP TABLE '. $attr .'log')){
		
		foreach($lines as $line){
			if(preg_match('#"'. preg_quote($attr) .'"#', $line)){
				
			}else{
				$newFile[] = $line;
			}
		}
		
		$fp = fopen("../bdd_lexiques.txt","w");
		foreach($newFile as $line){
			fputs($fp, $line);
		}
		fclose($fp);
		
		echo '<div class="list">Lexique supprimé<h3></h3></div>';
		
	}

}else{
	
	echo "Erreur 1";
	
}