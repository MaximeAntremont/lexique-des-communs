<?php session_start();
	
	include_once('../config.php');
	include_once('config.php');
	include_once('class/tools.php');
	include_once('class/entry.class.php');
	include_once('class/ressource.class.php');
	include_once('class/log.class.php');
	include_once('class/link.class.php');
	include_once('class/user.class.php');
	include_once('class/manager.class.php');
	
	if(isset($_GET['l'])){
		
		$l = htmlspecialchars($_GET['l']);
		$lines = file('admin/bdd_lexiques.txt', FILE_SKIP_EMPTY_LINES);

		foreach($lines as $line){
			if(preg_match('#"'. preg_quote($l) .'"#', $line)){
				
				if($line[0] != ";"){
				
					$_SESSION['lexique_attr'] = $l;
					break;
					
				}elseif(isConnected() && isSUDO()){
					
					$_SESSION['lexique_attr'] = $l;
					break;
					
				}
				
			}
		}
		
		if(isset($_SESSION['lexique_attr'])) header('Location:visualisation.php');
		
	}else{
		
		echo "rien d'indiqué";
		
	}