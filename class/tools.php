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
			
				$tempVal = $entry->val();
				if(  strtoupper($tempVal[0]) === $letter && isset($isWritten[$letter]) && $isWritten[$letter] == false){ //pourquoi avoir mit un "isset($isWritten[$letter])" ? Si "$isWritten[$letter]" n'est pas fait, ceci n'est-il pas égal à false ?
					echo('<span><a '.((strpos($selected,$letter) === 0)?'class="selected"':'').' href="visualisation.php?letter='.$letter.'">'.$letter.'</a></span>');
					$isWritten[$letter] = true;
					$letter++;
				}
				
			}
			
			if(isset($isWritten[$letter]) && $isWritten[$letter] == false){
				echo('<span>'.$letter.'</span>');
				$isWritten[$letter] = true;
				$letter++;
			}
				
			drawIndex($selected, $letter);
			
		}else if($selected != null && strpos($selected,$letter) != -1){
		
			$selectableEntries = $manager -> getEntryBy_mySelf('entry_val LIKE "'. $selected .'%"');
			echo('<br /><h1>'.$selectableEntries[0]->val().'</h1>');
			echo('<ul>');
			foreach($selectableEntries as $selectableEntry){
				echo('<li>'.$selectableEntry -> val().'</li>');
			}
			echo('</ul>');
			
		}
		
	}