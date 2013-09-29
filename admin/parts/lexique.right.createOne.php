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
	
	<input style="margin-top: 20px;" type="text" id="lex_name" placeholder="nom du lexique" maxlength="30" required />
	<input type="text" id="lex_attr" placeholder="attribut de table" maxlength="10" required />
	<div todo="submitNewLexique" class="listSelector">
		<h3>Créer le Lexique</h3>
	</div>
	
<?php
}