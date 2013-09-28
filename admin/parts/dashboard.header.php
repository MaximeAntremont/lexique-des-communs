<?php 
	if (session_id() == ''){
		session_start();
	} 
?>
<h1>DASHBOARD</h1>
<h3>Bienvenue, <?php echo $_SESSION['user_name']; ?>. :)</h3>