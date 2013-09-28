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
	
	if(isset($_POST['user_name']) && isset($_POST['user_pass'])){
		
		foreach($_POST as $key => $val){$_POST[$key] = htmlspecialchars($val);}
		
		$user = new User();
		$user->name($_POST['user_name']);
		$user->pass( sha1($_POST['user_pass']) );
		
		$out = $manager->loginUser($user);
		
		if( is_array($out) ){
			
			$_SESSION['user_id'] = $out['id'];
			$_SESSION['user_name'] = $out['name'];
			$_SESSION['user_type'] = $out['type'];
			$_SESSION['user_token'] = $out['token'];
			
			header('Location:../index.php');
			
		}else{
			echo 'Mauvaise authentification !';
		}
		
	}else{
		
		echo 'Mauvaise authentification !';
		
	}