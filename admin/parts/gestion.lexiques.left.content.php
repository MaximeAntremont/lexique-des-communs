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
?>

<div todo="dashboard" class="listSelector">
	<h3>Retour</h3>
</div>
<div todo="createLexique" class="listSelector">
	<h3>Créer un Lexique</h3>
</div>
<?php } ?>