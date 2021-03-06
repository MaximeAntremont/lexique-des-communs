<?php
	
	session_start();
	
	include_once('../../config.php');
	include_once('../config.php');
	include_once('../class/tools.php');
	include_once('../class/entry.class.php');
	include_once('../class/ressource.class.php');
	include_once('../class/log.class.php');
	include_once('../class/link.class.php');
	include_once('../class/user.class.php');
	include_once('../class/category.class.php');
	include_once('../class/manager.class.php');
	
	header('Content-type: application/json');
	
	// $manager = new Manager(getConnection());
	
	if(!empty($_POST['entry_id']) && is_string($_POST['entry_id']) && isset($_SESSION['lexique_attr']) ){
		
		$manager = new Manager(getConnection(), $_SESSION['lexique_attr']);
		
		
		$entry_id = htmlspecialchars($_POST['entry_id']);
		
		if($entry = $manager->getEntryBy_id( $entry_id, true, true )){
			
			$ressources = $entry->ressources();
			
			foreach($ressources as $ress){
			
				$type = $ress->type();
				if( isset($ress_dico[$type]) && isset($ress_dico[$type]['embed']) ){
					$dico = $ress_dico[$type];
					
					if(isset($dico['get'])){
						
						$answer = $dico['get']( $ress->val() );
						$answer = $dico['embed']( $answer );
						
						$ress->val( $answer );
						
					}else{
						
						$answer = $dico['embed']( $ress->val() );
						
						$ress->val( $answer );
						
					}
					
					// $ress->val( 
						// $dico['embed'](
								// (isset($dico['replace'])) ? preg_replace($dico['replace'], "", $ress->val() ) : ((isset($dico['get'])) ? preg_filter($dico['get'], "", $ress->val() ) : $ress->val())
							// ) 
					// );
				}
			
			}
			
			echo json_encode($entry->getArray());
			
		}else{
			
			echo json_encode( array('error' => false) );
			
		}
		
		
	}