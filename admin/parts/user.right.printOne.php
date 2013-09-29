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
		
		if(isSUDO()){
		?>
			<select id="input_user_type" style="margin-top:40px;" name="user_type">
				<option value="42" <?php echo ($user->type() == 42) ? "selected" : ""; ?> >Super Utilisateur</option>
				<option value="21" <?php echo ($user->type() == 21) ? "selected" : ""; ?> >Modérateur</option>
				<option value="10" <?php echo ($user->type() == 10) ? "selected" : ""; ?> >Rédacteur</option>
			</select>
			<div todo="changeType" user_id="<?php echo $user->id(); ?>" class="listSelector"><h3>Changer le type</h3></div>
		<?php } ?>
		
			<input id="input_user_pass1" style="margin-top:20px;" type="password" name="user_old_pass" placeholder="ancien mot de passe" />
			<input id="input_user_pass2" type="password" name="user_new_pass" placeholder="nouveau mot de passe" />
			<input id="input_user_pass3" type="password" name="user_new_pass2" placeholder="confirmation nouveau mot de passe" />
			<div todo="changePass" user_id="<?php echo $user->id(); ?>" class="listSelector"><h3>Changer le mot de passe</h3></div>
		</form>
		
		<?php
		
		if($user->type() != 0){
			
			echo '<div todo="deleteUser" user_id="'. $user->id() .'" class="listSelector">';
			echo '<h3>Supprimer ce compte</h3>';
			echo '<div>';
			
		}
		
	}else{
		echo '<div class="list">';
		echo '<h3>Erreur</h3>';
		echo '<div>';
	}