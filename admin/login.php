<?php
	
	session_start();
	
	$timestamp = time();
	
	if(isset($_SESSION['user_token']) && $_SESSION['user_token'] < $timestamp)
		header('Location:dashboard.php');
	elseif($_SESSION['user_token'])
		session_destroy();
	
include 'admin.header.php'; ?>
		
	<div id="left_panel" >
		<div id="header" class=""></div>
		
	</div>
	
	
	
	
	
	<div id="middle_panel" >
		<div id="header" class="on">
			<h1>Connexion</h1>
		</div>
		
		<form action="util/user.login.php" method="POST" style="margin-top: 40px;">
			<input type="text" name="user_name" id="user" placeholder="username" autofocus/>
			<input type="text" name="user_pass" id="pass" placeholder="pass"/>
			<input type="submit" value="Login !"/>
		</form>
		
	</div>
	
	
	
	
	
	<div id="right_panel" >
		<div id="header" class=""></div>
		
	</div>
		
<?php include 'admin.footer.php';