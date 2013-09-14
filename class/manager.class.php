<?php

class Manager
{
	private $_db;
	
	public function __construct($db)
	{
		$this->setDb($db);
	}
	
	public function setDb ($val){
		$this->_db = $val;
	}
	
	
	
	/********
	***********
	**	ENTRY PART
	***********
	*********/
	
	//setter
	public function sendNewEntry (Entry $obj){
		if( $this->isReadyToSend($obj) ){
		
			$req = $this->_db->prepare('INSERT INTO entry SET entry_val = :val');
			
			$req->bindValue(':val', $obj->val());
			
			$req->execute();
			
		}else{
			return false;
		}
		
	}
	
	//getters
	public function updateEntry (Entry $obj){
		$tempVal = $obj->val();
		$tempId = $obj->id();
		if( !empty($tempVal) && !empty($tempId) && is_numeric($tempId) ){
		
			$req = $this->_db->prepare('UPDATE entry SET entry_val = :val WHERE entry_id = :id');
			
			$req->bindValue(':id', $obj->id());
			$req->bindValue(':val', $obj->val());
			
			$req->execute();
		}else{
			return false;
		
		}
		
	}
	
	public function getEntryAll (){
		
		$entrys = array();
		
		$req = $this->_db->query('SELECT * FROM entry');
		
		while($don = $req->fetch()){
			$entrys[] = new Entry($don);
		}
		
		return $entrys;
		
	}
	
	public function getEntryBy_id ($id){
		
		if(is_numeric($id)){
			
			$req = $this->_db->query('SELECT * FROM entry WHERE entry_id = '.$id);
			
			return ($don = $req->fetch()) ? new Entry($don) : false;
			
		}else{
			return false;
		}
		
	}
	
	
	//isEntry...
	public function isEntrySet ($entry){
		
		$req = $this->_db->query('SELECT entry_id FROM entry WHERE entry_val = "'.$entry->val().'"');
		
		return ($don = $req->fetch()) ? true : false;
		
	}
	
	
	/********
	***********
	**	CATEGORY PART
	***********
	*********/
	
	public function sendNewCategory (Category $obj){
		
		$req = $this->_db->prepare('INSERT INTO category SET category_val = :val');
		
		$req->bindValue(':val', $obj->val());
		
		$req->execute();
		
	}
	
	public function updateCategory (Category $obj){
		
		$req = $this->_db->prepare('UPDATE category SET category_val = :val WHERE category_id = :id');
		
		$req->bindValue(':id', $obj->id());
		$req->bindValue(':val', $obj->val());
		
		$req->execute();
		
	}
	
	public function getCategory (){
		
	}
	
	
	
	/********
	***********
	**	LINK PART
	***********
	*********/
	
	public function sendNewLink (Link $obj){
		
		$req = $this->_db->prepare('INSERT INTO link SET 
			link_val = :val,
			link_from = :from,
			link_to = to:,
			link_type = :type,
			link_entry_id = :entry_id ');
		
		$req->bindValue(':val', $obj->val());
		$req->bindValue(':from', $obj->from());
		$req->bindValue(':to', $obj->to());
		$req->bindValue(':type', $obj->type());
		$req->bindValue(':entry_id', $obj->entry_id());
		
		$req->execute();
		
	}
	
	public function updateLink (Link $obj){
		
		$req = $this->_db->prepare('UPDATE entry SET
			link_val = :val,
			link_from = :from,
			link_to = to:,
			link_type = :type,
			link_entry_id = :entry_id 
			WHERE link_id = :id');
		
		$req->bindValue(':val', $obj->val());
		$req->bindValue(':from', $obj->from());
		$req->bindValue(':to', $obj->to());
		$req->bindValue(':type', $obj->type());
		$req->bindValue(':entry_id', $obj->entry_id());
		$req->bindValue(':id', $obj->id());
		
		$req->execute();
		
	}
	
	public function getLink (){
		
	}
	
	
	
	/********
	***********
	**	LOG PART
	***********
	*********/
	
	public function sendNewLog (Log $obj){
		
		$req = $this->_db->prepare('INSERT INTO log SET 
			log_val = :val,
			log_entry_id = :entry_id,
			log_ip = :ip,
			log_type = :type');
		
		$req->bindValue(':val', $obj->val());
		$req->bindValue(':entry_id', $obj->entry_id());
		$req->bindValue(':ip', $obj->ip());
		$req->bindValue(':type', $obj->type());
		
		$req->execute();
		
	}
	
	public function updateLog (Log $obj){
		
		$req = $this->_db->prepare('UPDATE log SET 
			log_val = :val,
			log_entry_id = :entry_id,
			log_ip = :ip,
			log_type = :type
			WHERE log_id = :id');
		
		$req->bindValue(':val', $obj->val());
		$req->bindValue(':entry_id', $obj->entry_id());
		$req->bindValue(':ip', $obj->ip());
		$req->bindValue(':type', $obj->type());
		$req->bindValue(':id', $obj->id());
		
		$req->execute();
		
	}
	
	public function getLog (){
		
	}
	
	
	
	/********
	***********
	**	RESSOURCE PART
	***********
	*********/
	
	public function sendNewRessource (Ressource $obj){
		
		$req = $this->_db->prepare('INSERT INTO ressource SET 
			ress_val = :val,
			ress_type = :type,
			ress_trend = :trend,
			ress_alert = :alert,
			ress_entry_id = :entry_id,
			ress_category_id = :category_id');
		
		$req->bindValue(':val', $obj->val());
		$req->bindValue(':type', $obj->type());
		$req->bindValue(':trend', $obj->trend());
		$req->bindValue(':alert', $obj->alert());
		$req->bindValue(':entry_id', $obj->entry_id());
		$req->bindValue(':category_id', $obj->category_id());
		
		$req->execute();
		
	}
	
	public function updateRessource (Ressource $obj){
		
		$req = $this->_db->prepare('UPDATE ressource SET 
			ress_val = :val,
			ress_type = :type,
			ress_trend = :trend,
			ress_alert = :alert,
			ress_entry_id = :entry_id,
			ress_category_id = :category_id
			WHERE ress_id = :id');
		
		$req->bindValue(':val', $obj->val());
		$req->bindValue(':type', $obj->type());
		$req->bindValue(':trend', $obj->trend());
		$req->bindValue(':alert', $obj->alert());
		$req->bindValue(':entry_id', $obj->entry_id());
		$req->bindValue(':category_id', $obj->category_id());
		$req->bindValue(':id', $obj->id());
		
		$req->execute();
		
	}
	
	public function getRessource (){
		
	}
	
	
	
	/********
	***********
	**	USER PART
	***********
	*********/
	
	public function sendNewUser (User $obj){
		
		$req = $this->_db->prepare('INSERT INTO user SET 
			user_name = :name,
			user_pass = :pass,
			user_type = :type');
		
		$req->bindValue(':name', $obj->name());
		$req->bindValue(':pass', $obj->pass());
		$req->bindValue(':type', $obj->type());
		
		$req->execute();
		
	}
	
	public function updateUser (User $obj){
		
		$req = $this->_db->prepare('UPDATE user SET 
			user_name = :name,
			user_pass = :pass,
			user_type = :type
			WHERE user_id = :id');
		
		$req->bindValue(':name', $obj->name());
		$req->bindValue(':pass', $obj->pass());
		$req->bindValue(':type', $obj->type());
		$req->bindValue(':id', $obj->id());
		
		$req->execute();
		
	}
	
	public function getUser (){
		
	}
	
	
	
	/********
	***********
	**	CHECK FUNCTIONS
	***********
	*********/
	
	public function isReadyToSend ($obj){
		
		if($obj instanceof Entry){
			
			$val = $obj->val();
			return (empty($val)) ? false : true;
		
		}elseif($obj instanceof Ressource){
		
		
		}elseif($obj instanceof Link){
		
		
		}elseif($obj instanceof User){
		
		
		}elseif($obj instanceof Category){
		
		
		}elseif($obj instanceof Log){
		
		
		}else{
		
			return false;
		
		}
		
	}
}