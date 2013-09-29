<?php 
	
	session_start();
	
	include_once('../config.php');
	include_once('config.php');
	include_once('class/tools.php');
	include_once('class/entry.class.php');
	include_once('class/ressource.class.php');
	include_once('class/log.class.php');
	include_once('class/link.class.php');
	include_once('class/user.class.php');
	include_once('class/manager.class.php');
		
	$lines = file('admin/bdd_lexiques.txt', FILE_SKIP_EMPTY_LINES);

	foreach($lines as $line){
		if(preg_match('#([^"]*)"([^"]*)"#', $line, $out)){
			
			if($line[0] != ";"){
			
				echo '<a href="lexique.php?l='. $out[2] .'">'. $out[1] .'<a/><br/><br/>';
				
			}elseif(isConnected() && isSUDO()){
				
				$out[1][0] = '';
				
				echo '<a href="lexique.php?l='. $out[2] .'">'. $out[1] .' [inactive]<a/><br/><br/>';
				
			}
			
		}
	}