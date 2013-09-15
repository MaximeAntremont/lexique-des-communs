<?php
	
	include_once('config.php');
	include_once('../config.php');
	include_once('class/tools.php');
	include_once('class/entry.class.php');
	include_once('class/ressource.class.php');
	include_once('class/log.class.php');
	include_once('class/link.class.php');
	include_once('class/user.class.php');
	include_once('class/manager.class.php');
	
	$manager = new Manager(getConnection());
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Formulaire d'ajout temporaire</title>
		<style type="text/css">
			*{
				margin: 0;
				padding: 0;
				font-family: sans-serif;
			}
			body{
				background: rgb(250,250,250);
			}
			
			.window{
				width: 300px;
				height: 100px;
				padding: 10px;
			}
			h1{
				font-size: 22px;
				text-align: center;
			}
		</style>
	</head>
	<body>
		
		<div style="width:650px; margin:auto;margin-top: 200px;">
			<div class="window" style="float: left;border-right: 1px solid rgba(0,0,0,0.2);">
				<h1>Choisir une entrée</h1>
				<p>
					Choisissez une entrée pour l'agrémenter de votre savoir, de vos idées et autres !<br/><br/>
				</p>
				<form action="ressource.test.php" method="GET" >
					<select name="tempId" style="margin-left: 75px;width: 100px;float:left;height:23px;margin-top:1px;">
						<?php
							if(!$manager->isHS()){
							
								$all = $manager->getEntryAll();
								
								foreach($all as $entry){
									echo '<option value="'.$entry->id().'">'.$entry->val().'</option>';
								}
								
								echo "<br/>";
								
							}
						?>
					</select>
					<input type="submit" style="float:left;" value="suivant" />
				</form>
			</div>
			
			<form class="window" style="float: right;border-left: 1px solid rgba(0,0,0,0.2);" action="tempForm.php" method="POST" >
					<h1>Ajouter une entrée</h1>
					<p>Il vous est possible d'ajouter un mot pour, ensuite, y intégrer des ressources et créer des liens.</p>
					<input type="text" name="entry_val" style="margin-left: 75px;width: 100px;float:left;height:23px;margin-top:1px;"  />
					<input type="Submit" value="suivant" style="float:left;" />
			</form>
		</div>
	</body>
</html>