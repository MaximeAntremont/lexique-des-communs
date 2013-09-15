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
	
	function drawIndex($selected = null, $letter = 'A', $isWritten = array()){
		$isWritten[$letter] = false;
		global $manager;
		global $entries;
		
		if($letter != 'AA'){
			foreach($entries as $entry){
				if((strpos($entry->val(), $letter) === 0 || strpos($entry->val(), strtolower($letter)) === 0) && isset($isWritten[$letter]) && $isWritten[$letter] == false){
					echo('<a '.((strpos($selected,$letter) === 0)?'class="selected"':'').' href="visualisation.php?letter='.$letter.'">'.$letter.'</a>'.(($letter == 'Z')?'':'-'));
					$isWritten[$letter] = true;
					$letter++;
				}
			}
			if(isset($isWritten[$letter]) && $isWritten[$letter] == false){
				echo('<span>'.$letter.'</span>'.(($letter == 'Z')?'':'-'));
				$isWritten[$letter] = true;
				$letter++;
			}
				
			drawIndex($selected, $letter);
		}
		else if($selected != null && strpos($selected,$letter) != -1){
			$selectableEntries = $manager -> getEntryByLetter($selected);
			echo('<br /><h1>'.$selectableEntries[0]->val().'</h1>');
			echo('<ul>');
			foreach($selectableEntries as $selectableEntry){
				echo('<li>'.$selectableEntry -> val().'</li>');
			}
			echo('</ul>');
		}
	}