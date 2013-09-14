﻿<?php
	
	include_once('config.php');
	include_once('../config.php');
	include_once('class/tools.php');
	include_once('class/entry.class.php');
	include_once('class/ressource.class.php');
	include_once('class/log.class.php');
	include_once('class/link.class.php');
	include_once('class/user.class.php');
	include_once('class/manager.class.php');
	
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Formulaire d'ajout temporaire</title>
		<style type="text/css">
			fieldset{
				width: 250px;
			}
			
			label{
				display: block;
			}
			
			input[type="text"]{
				float: right;
			}
		</style>
	</head>
	<body>
		
		
		<form action="tempForm.php" method="POST" >
			<fieldset>
				<legend>Entry</legend>
				<?php
					$manager = new Manager(getConnection());
					
					if(!$manager->isHS()){
					
						$all = $manager->getEntryAll();
						
						foreach($all as $entry){
							
							echo $entry->id()." - ".$entry->val()."<br/>";
							
						}
						
						echo "<br/>";
						
					}
				?>
				<label>Value<input type="text" name="entry_val" /></label><br />
				<input type="Submit" />
			</fieldset>
		</form>
		
		
		<form action="tempForm.php" method="POST" >
			<fieldset>
				<legend>Ressource</legend>
				<?php
					if(!$manager->isHS()){
					
						$all = $manager->getRessourceAll();
						
						foreach($all as $ress){
							
							echo $ress->id()." - ".$ress->entry_id()." - ".$ress->val()."<br/>";
							
						}
						
						echo "<br/>";
						
					}
				?>
				<label>Entry<select name="ress_entry_id">
				<?php
					$manager = new Manager(getConnection());
					
					if(!$manager->isHS()){
					
						$all = $manager->getEntryAll();
						
						foreach($all as $entry){
							echo '<option value="'.$entry->id().'">'.$entry->val().'</option>';
						}
						
						echo "<br/>";
						
					}
				?>
				</select></label><br />
				<label>Category ID<input type="text" name="ress_category_id" /></label><br />
				<label>Type<select name="ress_type">
					<option value="100">vidéo</option>
					<option value="200">image</option>
					<option value="300">son</option>
					<option value="400">texte</option>
					<option value="500" selected>lien</option>
				</select></label><br />
				<label>Value<input type="text" name="ress_val" /></label><br />
				<input type="Submit" />
			</fieldset>
		</form>
		
		<form action="tempForm.php" method="POST" >
			<fieldset>
				<legend>Link</legend>
				<?php
					if(!$manager->isHS()){
					
						$all = $manager->getLinkAll();
						
						foreach($all as $link){
							
							echo $link->id()." - ".$link->type()." - ".$link->from()." -> ".$link->to()." - ".$link->val()."<br/>";
							
						}
						
						echo "<br/>";
						
					}
				?>
				<label>Entry<select name="link_entry_id">
				<?php
					$manager = new Manager(getConnection());
					
					if(!$manager->isHS()){
					
						$all = $manager->getEntryAll();
						
						foreach($all as $entry){
							echo '<option value="'.$entry->id().'">'.$entry->val().'</option>';
						}
						
					}
				?>
				</select></label><br />
				
				<label>From<select name="link_from" >
				<?php
					if(!$manager->isHS()){
					
						$all = $manager->getRessourceAll();
						
						foreach($all as $ress){
							
							echo '<option value="'.$ress->id().'">'.$ress->id().'</option>';
							
						}						
					}
				?></select></label><br />
				
				<label>To<select name="link_to" >
				<?php
					if(!$manager->isHS()){
					
						$all = $manager->getRessourceAll();
						
						foreach($all as $ress){
							
							echo '<option value="'.$ress->id().'">'.$ress->id().'</option>';
							
						}
						
						echo "<br/>";
						
					}
				?></select></label><br />
				
				<label>Value<input type="text" name="link_val" /></label><br />
				<label>Type<select name="link_type">
					<option value="000" selected>Rien</option>
					<option value="100">vidéo</option>
					<option value="200">image</option>
					<option value="300">son</option>
					<option value="400" >texte</option>
					<option value="500">lien</option>
				</select></label><br />
				<p>Note: ajouter Alert dans Link (class)</p>
				<input type="Submit" />
			</fieldset>
		</form>
		
		<!--
		<form action="tempForm.php" method="POST" >
			<fieldset>
				<legend>User</legend>
				<label>Name<input type="text" name="user_name" /></label><br />
				<label>Password<input type="text" name="user_pass" /></label><br />
				<label>Type<input type="text" name="user_type" /></label><br />
				<input type="Submit" />
			</fieldset>
		</form>
		-->
	</body>
</html>