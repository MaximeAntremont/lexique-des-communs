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

	
	if(isset($_POST['user_id']) && isConnected() && (isSUDO() || $_POST['user_id'] == $_SESSION['user_id']) ){
		
		$manager = new Manager(getConnection());
		$user = $manager->getUserBy_id(htmlspecialchars($_POST['user_id']));
		
		?>
		<form style="margin-top:20px;">
			<input type="text" name="user_name" value="<?php echo $user->name(); ?>" />
			<input type="submit" value="Changer le nom" />
		</form>
		<form style="margin-top:20px;">
			<select name="user_type">
				<option value="42" <?php echo ($user->type() == 42) ? "selected" : ""; ?> >Super Utilisateur</option>
				<option value="21" <?php echo ($user->type() == 21) ? "selected" : ""; ?> >Modérateur</option>
				<option value="10" <?php echo ($user->type() == 10) ? "selected" : ""; ?> >Rédacteur</option>
			</select>
			<input type="submit" value="Changer le type" />
		</form>
		<form style="margin-top:20px;border-bottom:1px solid rgb(200,200,200);padding-bottom:25px;">
			<input type="password" name="user_old_pass" placeholder="ancien mot de passe" />
			<input type="password" name="user_new_pass" placeholder="nouveau mot de passe" />
			<input type="password" name="user_new_pass2" placeholder="confirmation nouveau mot de passe" />
			<input type="submit" value="Changer de mot de passe" />
		</form>
		
		<?php
		
		if($user->type() != 0){
			
			echo '<div class="listSelector">';
			echo '<h3>Supprimer ce compte</h3>';
			echo '<div>';
			
		}
		
	}else{
		echo '<div class="list">';
		echo '<h3>Erreur</h3>';
		echo '<div>';
	}