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
	
	if( !empty($_POST['user_old_pass'])
	&& !empty($_POST['user_new_pass'])
	&& !empty($_POST['user_new_pass2'])
	&& !empty($_POST['user_id'])
	&& isConnected() && (isSUDO() || $_POST['user_id'] == $_SESSION['user_id']) ){
		
		foreach($_POST as $key => $val){$_POST[$key] = htmlspecialchars($val);}
		
		$user = $manager->getUserBy_id($_POST['user_id']);
		
		if($user->pass() == sha1($_POST['user_old_pass']))	{
			
			if($_POST['user_new_pass'] == $_POST['user_new_pass2']){
				
				$user->pass( sha1($_POST['user_new_pass']) );
				
				if($manager->updateUser($user)){
					echo '<div class="list"><h3>Changement effectué</h3></div>';
					session_destroy();
					header('Location:../login.php');
				}else echo 'Impossible 4';
				
			}else echo 'Impossible 3';
			
		}else echo 'Impossible 2';
		
	}else{
		
		echo 'Impossible 1';
		
	}