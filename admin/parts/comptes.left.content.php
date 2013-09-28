 <?php session_start();

	$timestamp = time();
	
	include_once('../../../config.php');
	include_once('../../config.php');
	include_once('../../class/tools.php');
	include_once('../../class/entry.class.php');
	include_once('../../class/ressource.class.php');
	include_once('../../class/log.class.php');
	include_once('../../class/link.class.php');
	include_once('../../class/user.class.php');
	include_once('../../class/manager.class.php');

	
	if(isset($_SESSION['user_token']) && $_SESSION['user_token'] < $timestamp){
		
		?>
		
		<div id="dashboard" class="listSelector">
			<h3>Retour</h3>
		</div>
		<div id="" class="listSelector">
			<h3>Créer un nouveau compte</h3>
		</div>
		
		<?php
	}