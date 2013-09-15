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
	
	function drawIndex($letter = 'A', $isWritten = array()){
		$isWritten[$letter] = false;
		global $entries;
		
		if($letter != 'AA'){
			foreach($entries as $entry){
				if(strpos($entry->val(), $letter) === 0 && isset($isWritten[$letter]) && $isWritten[$letter] == false){
					echo('<a href="">'.$letter.'</a>'.(($letter == 'Z')?'':'-'));
					$isWritten[$letter] = true;
					$letter++;
				}
			}
			
			if(isset($isWritten[$letter]) && $isWritten[$letter] == false){
				echo('<span>'.$letter.'</span>'.(($letter == 'Z')?'':'-'));
				$isWritten[$letter] = true;
				$letter++;
			}
				
			drawIndex($letter);
		}
	}