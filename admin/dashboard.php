<?php
	
	session_start();
	
	$timestamp = time();
	
	if(!isset($_SESSION['user_token']) || $_SESSION['user_token'] >= $timestamp){
		session_destroy();
		header('Location:index.php');
	}else{
	
include 'admin.header.php'; ?>
		
	<div id="left_panel" >
		<div id="header" class="on">
			<h1>DASHBOARD</h1>
			<h3>Bienvenue, <?php echo $_SESSION['user_name']; ?>. :)</h3>
		</div>
		
		<div id="content">
			<div id="gestionLexiques" class="listSelector">
				<h3>Gérer mes lexiques</h3>
			</div>
			<div id="gestionUsers" class="listSelector">
				<h3>Gérer les utilisateurs</h3>
			</div>
			<div id="gestionLogs" class="listSelector">
				<h3>Voir les rapports de bug</h3>
			</div>
			<div id="gestionOwn" class="listSelector">
				<h3>Gérer mon compte</h3>
			</div>
		</div>
		
	</div>
	
	
	
	
	
	<div id="middle_panel" >		
		<div id="content">
		</div>
	</div>
	
	
	
	
	
	<div id="right_panel" >	
		<div id="content">
		</div>
	</div>
		
<?php include 'admin.footer.php'; }