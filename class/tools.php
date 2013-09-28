<?php

	function getConnection (){
		try {
			$db = new PDO("mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME.";",DB_USERNAME,DB_PASSWORD,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $db;
		} catch (PDOException $e) {
			echo 'Échec lors de la connexion : ' . $e->getMessage();
			return false;
		}
	}
	
	function isConnected (){
		
		$timestamp = time();
		return (isset($_SESSION['user_token']) && $_SESSION['user_token'] < $timestamp) ? true : false;
		
	}
	
	function isSUDO (){
		
		$timestamp = time();
		return (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 42) ? true : false;
		
	}
	
	function isMODO (){
		
		$timestamp = time();
		return (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 21) ? true : false;
		
	}