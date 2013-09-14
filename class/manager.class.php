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
	
	public function sendNewEntry (Entry $obj){
		
		$req = $this->_db->prepare('INSERT INTO entry SET entry_val = :val');
		
		$req->bindValue(':val', $obj->val());
		
		$req->execute();
		
	}
	
	public function updateEntry (Entry $obj){
		
		$req = $this->_db->prepare('UPDATE entry SET entry_val = :val WHERE entry_id = :id');
		
		$req->bindValue(':id', $obj->id());
		$req->bindValue(':val', $obj->val());
		
		$req->execute();
		
	}
	
	public function getEntry (){
		
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
	
	
}