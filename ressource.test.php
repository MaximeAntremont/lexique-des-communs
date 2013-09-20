<?php
	
	include_once('config.php');
	include_once('../config.php');
	include_once('class/tools.php');
	include_once('class/entry.class.php');
	include_once('class/ressource.class.php');
	include_once('class/log.class.php');
	include_once('class/link.class.php');
	include_once('class/user.class.php');
	include_once('class/category.class.php');
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
				min-height: 250px;
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
		<?php if( !empty($_GET['tempId']) && is_numeric($_GET['tempId']) ): ?>
		
			<form class="window" style="float: left;border-right: 1px solid rgba(0,0,0,0.2);" action="tempForm.php" method="POST" >
				<h1>Ajouter une ressource</h1>
				<p>Il vous est possible d'ajouter une ressource que vous pourrez lier avec une autre dans le panneaux de droite.</p>
				<label style="visibility: hidden;">Entry_id<input name="ress_entry_id" value="<?php echo $_GET['tempId']; ?>" /></label><br />
				<label>Catégorie<select name="ress_category_id">
				<?php
					if(!$manager->isHS()){
						$all = $manager->getCategoryAll();
						foreach($all as $cat){
							echo '<option value="'.$cat->id().'" >'.$cat->val().'</option>';
						}
					}
				?>
				</select></label><br />
				<label>Type<select name="ress_type">
					<option value="100">vidéo</option>
					<option value="200">image</option>
					<option value="300">son</option>
					<option value="400">texte</option>
					<!--<option value="500" selected>lien</option>-->
				</select></label><br />
				<input type="text" name="ress_val" placeholder="value" style="width: 100px;height:23px;margin-top:1px;"  /><br/>
				<input type="Submit" value="valider"/><br/>
				<?php
					if(!$manager->isHS()){
					
						$all = $manager->getRessourceBy_entry_id($_GET['tempId']);
						
						foreach($all as $ress){
							
							echo "[".$ress->id()."] ".$ress->val()."<br/>";
							
						}
						
					}
				?>
			</form>
			
			<form class="window" style="float: right;border-left: 1px solid rgba(0,0,0,0.2);" action="tempForm.php" method="POST" >
					<h1>Ajouter un lien</h1>
					<p>Il vous est possible de créer un lien entre les ressources.</p>
					<label style="visibility: hidden;">Entry_id<input name="link_entry_id" value="<?php echo $_GET['tempId']; ?>" /></label><br />
					<label>From<select name="link_from" >
					<?php
						if(!$manager->isHS()){
						
							$all = $manager->getRessourceBy_entry_id($_GET['tempId']);
							
							foreach($all as $ress){
								
								echo '<option value="'.$ress->id().'">'.$ress->id().'</option>';
								
							}						
						}
					?></select></label><br />
					<label>To<select name="link_to" >
					<?php
						if(!$manager->isHS()){
						
							$all = $manager->getRessourceBy_entry_id($_GET['tempId']);
							
							foreach($all as $ress){
								
								echo '<option value="'.$ress->id().'">'.$ress->id().'</option>';
								
							}
														
						}
					?></select></label><br />
					<input type="text" placeholder="value" name="link_val" /><br />
					<label>Type<select name="link_type">
					<option value="0" selected>conflitctuel</option>
					<option value="100">implicite</option>
					<option value="200">explicite</option>
					<option value="300">direct</option>
				</select></label><br />
					<input type="Submit" value="valider" /><br/>
					<?php
					if(!$manager->isHS()){
					
						$all = $manager->getLinkBy_entry_id($_GET['tempId']);
						
						foreach($all as $link){
							
							echo $link->type()." [".$link->from().">".$link->to()."] ".$link->val()."<br/>";
							
						}
												
					}
				?>
			</form>
			
		<?php endif; ?>
			
			
		</div>
	</body>
</html>