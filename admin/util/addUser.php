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
	
	if(isConnected() && isSUDO()){
		
		foreach($_POST as $key => $val){$_POST[$key] = htmlspecialchars($val);}
		
		$user = new User();
		$user->name($_POST['user_name']);
		
		if($_POST['user_pass1'] === $_POST['user_pass2']){
		
			$user->pass( sha1($_POST['user_pass1']) );
			$user->type( $_POST['user_type'] );
			
			if($manager->sendNewUser($user)){
				header('Location:../dashboard.php');
			}else{
				echo 'Impossible 3';
				echo $user;
			}
			
		}else
			echo 'Impossible 2';
	}else{
		
		echo 'Impossible 1';
		
	}