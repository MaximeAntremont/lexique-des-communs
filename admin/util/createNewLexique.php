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
	
	if(isset($_POST['lex_attr']) && isset($_POST['lex_name']) && isConnected() && isSUDO()){
		
		foreach($_POST as $k => $v){$_POST[$k] = htmlspecialchars($v);}
		
		$db = getConnection();
		$attr = $_POST['lex_attr'].'_';
		$name = $_POST['lex_name'];
		$OK = false;
		
		$correctValues = (preg_match("#([_a-zA-Z0-9]|\s){1,30}#", $name) && preg_match("#[_a-zA-Z0-9]{1,10}#", $attr)) ? true : false;		
		
		if($correctValues){
			$fp = fopen("../bdd_lexiques.txt","a");
			if(fputs($fp, "\n") && fputs($fp, $name.'"'. $attr .'"')){
				
				$category = $db->query('
					CREATE TABLE '. $attr .'category (
						`category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						`category_val` varchar(25) NOT NULL,
						`category_create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
						PRIMARY KEY (`category_id`)
					)
				');
				
				$entry = $db->query('
					CREATE TABLE '. $attr .'entry (
						`entry_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						`entry_val` varchar(50) NOT NULL,
						`entry_create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
						PRIMARY KEY (`entry_id`)
					)
				');
				
				$link = $db->query('
					CREATE TABLE '. $attr .'link (
						`link_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						`link_entry_id` int(11) unsigned NOT NULL,
						`link_from` int(10) unsigned NOT NULL,
						`link_to` int(10) unsigned NOT NULL,
						`link_val` varchar(20) DEFAULT NULL,
						`link_type` int(10) unsigned NOT NULL,
						`link_create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
						`link_alert` int(10) unsigned DEFAULT \'0\',
						PRIMARY KEY (`link_id`)
					)
				');
				
				$log = $db->query('
					CREATE TABLE '. $attr .'log (
						`log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						`log_type` int(10) unsigned DEFAULT NULL,
						`log_val` text,
						`log_create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
						`log_ip` varchar(255) DEFAULT NULL,
						`log_entry_id` int(10) unsigned DEFAULT NULL,
						PRIMARY KEY (`log_id`)
					)
				');
				
				$ressource = $db->query('
					CREATE TABLE '. $attr .'ressource (
						`ress_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						`ress_entry_id` int(10) unsigned NOT NULL,
						`ress_category_id` int(10) unsigned DEFAULT NULL,
						`ress_type` int(11) unsigned NOT NULL,
						`ress_val` text NOT NULL,
						`ress_trend` int(11) NOT NULL DEFAULT \'0\',
						`ress_create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
						`ress_alert` int(11) unsigned NOT NULL DEFAULT \'0\',
						`ress_titre` varchar(20) DEFAULT NULL,
						PRIMARY KEY (`ress_id`)
					)
				');
				
				if($category && $entry && $link & $log && $ressource) $OK = true;
				
			}else echo "erreur 3";
			
			fclose($fp);
			if($OK) header('Location:../dashboard.php');
			
		}else echo "erreur 2";
		
		
		
		
	}else echo "erreur 1";
	
	
	
	
	
	
	