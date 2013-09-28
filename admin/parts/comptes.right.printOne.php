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

	
	if(isset($_POST['user_id']) && isset($_SESSION['user_token']) && $_SESSION['user_token'] < $timestamp){
		
		$manager = new Manager(getConnection());
		$user = $manager->getUserBy_id(htmlspecialchars($_POST['user_id']));
		
		?>
		<form style="margin-top:20px;">
			<input type="text" name="user_name" value="<?php echo $user->name(); ?>" />
			<input type="submit" value="Changer le nom" />
		</form>
		<form style="margin-top:20px;">
			<input type="text" name="user_old_pass" placeholder="ancien mot de passe" />
			<input type="text" name="user_new_pass" placeholder="nouveau mot de passe" />
			<input type="text" name="user_new_pass2" placeholder="confirmation nouveau mot de passe" />
			<input type="submit" value="Changer de mot de passe" />
		</form>
		
		<?php
	}