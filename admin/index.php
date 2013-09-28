<?php
	session_start();
	
	if(!isset($_SESSION['user_token']) || $_SESSION['user_token'] >= $timestamp)
		header('Location:login.php');