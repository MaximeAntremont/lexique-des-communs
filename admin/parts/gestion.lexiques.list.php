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
	
if(isConnected() && isSUDO()){

	$lines = file('../bdd_lexiques.txt', FILE_SKIP_EMPTY_LINES);

	foreach($lines as $line){
		
		preg_match('#([^"]+)"([^"]+)"#i', $line, $out);
		
		$lexique  = '<div lexique="'. $out[2] .'" class="listSelector">';
		$lexique .= '<h3>'. $out[1] .'</h3>';
		$lexique .= '</div>';
		
		echo $lexique;
	}

}