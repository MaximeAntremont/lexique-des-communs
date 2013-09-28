<?php

	if (session_id() == ''){
		session_start();
	}

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
	
		<div todo="sectionLexique" id="gestionLexiques" class="listSelector">
			<h3>Gérer mes lexiques</h3>
		</div>
		<div todo="sectionUsers" id="gestionUsers" class="listSelector">
			<h3>Gérer les utilisateurs</h3>
		</div>

	<?php } ?>

	<div todo="sectionLogs" id="gestionLogs" class="listSelector">
		<h3>Voir les rapports de bug</h3>
	</div>
	<div todo="sectionOwn" id="gestionOwn" class="listSelector">
		<h3>Gérer mon compte</h3>
	</div>
	<a href="logout.php">
		<div id="dashExit" class="listSelector">
			<h3>Déconnexion</h3>
		</div>
	</a>