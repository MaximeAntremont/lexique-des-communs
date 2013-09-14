<?php
	
	include_once('../config.php');
	include_once('class/tools.php');
	include_once('class/entry.class.php');
	// include_once('class/ressource.class.php');
	// include_once('class/log.class.php');
	// include_once('class/link.class.php');
	// include_once('class/user.class.php');
	include_once('class/manager.class.php');
	
?>
<html>
	<head>
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
		
		<?php
			
			if($db = getConnection()){
			
				$manager = new Manager($db);
				$all = $manager->getEntryAll();
				
				foreach($all as $entry){
					
					echo $entry->id()." - ".$entry->val()."<br/>";
					
				}
				
				echo "<br/>";
				
			}
			
		?>
		
		<form action="tempForm.php" method="POST" >
			<fieldset>
				<legend>Entry</legend>
				<label>Value<input type="text" name="entry_val" /></label><br />
				<input type="Submit" />
			</fieldset>
		</form>
		
		<!--
		<form action="tempForm.php" method="POST" >
			<fieldset>
				<legend>Ressource</legend>
				<label>Entry ID<input type="text" name="ress_entry_id" /></label><br />
				<label>Category ID<input type="text" name="ress_category_id" /></label><br />
				<label>Type<input type="text" name="ress_type" /></label><br />
				<label>Value<input type="text" name="ress_val" /></label><br />
				<label>Trend<input type="text" name="ress_trend" /></label><br />
				<label>Alert<input type="text" name="ress_alert" value="0" /></label><br />
				<input type="Submit" />
			</fieldset>
		</form>
		
		<form action="tempForm.php" method="POST" >
			<fieldset>
				<legend>Link</legend>
				<label>Entry ID<input type="text" name="link_entry_id" /></label><br />
				<label>From<input type="text" name="link_from" /></label><br />
				<label>To<input type="text" name="link_to" /></label><br />
				<label>Value<input type="text" name="link_val" /></label><br />
				<label>Type<input type="text" name="link_type" /></label><br />
				<label>Alert<input type="text" name="link_alert" value="0" /></label><br />
				<input type="Submit" />
			</fieldset>
		</form>
		
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