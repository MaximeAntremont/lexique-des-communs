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
		
		// vérifier la longueur de la chaîne et ses charctères (seulement [a-zA-Z0-9])
		
		
		$db->query('
			CREATE TABLE '. $attr .'entry (
				
				
				
			)
		');
		
	}
	
mysql_query("CREATE TABLE example(
id INT NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(id),
 name VARCHAR(30), 
 age INT)")
 or die(mysql_error());