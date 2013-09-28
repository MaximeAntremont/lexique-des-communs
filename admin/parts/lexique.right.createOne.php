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
	
if(isConnected() && isSUDO()){ ?>
	
	<form action="util/createNewLexique.php" method="POST" style="margin-top: 20px;" >
		<input type="text" name="lex_name" placeholder="nom du lexique" maxlength="30" required />
		<input type="text" name="lex_attr" placeholder="attribut de table" maxlength="10" required />
		<input type="submit" value="Créer" />
	</form>
	
<?php
}