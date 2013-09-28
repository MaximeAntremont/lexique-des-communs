<?php
	session_start();
	
	include_once('../../../config.php');
	include_once('../../config.php');
	include_once('../../class/tools.php');
	include_once('../../class/entry.class.php');
	include_once('../../class/ressource.class.php');
	include_once('../../class/log.class.php');
	include_once('../../class/link.class.php');
	include_once('../../class/user.class.php');
	include_once('../../class/manager.class.php');

	$manager = new Manager(getConnection());
	
	if(!empty($_POST['user_id'])
	&& isConnected() && (isSUDO() || $_POST['user_id'] == $_SESSION['user_id']) ){
		
		foreach($_POST as $key => $val){$_POST[$key] = htmlspecialchars($val);}
		
		if($manager->removeUserBy_id($_POST['user_id'])){
			
			echo '<div class="list">';
			echo '<h3>Compte supprimé</h3>';
			echo '</div>';
			
		}else{
			
			echo '<div class="list">';
			echo '<h3>Echec de la suppression</h3>';
			echo '</div>';
		
		}
		
	}else{
		
		echo 'Impossible 1';
		
	}