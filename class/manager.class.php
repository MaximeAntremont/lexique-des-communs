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
	
	public function isHS (){
		return (empty($this->_db)) ? true : false;
	}
	
	
	/**************************************************************************
	***************************************************************************
	**	ENTRY PART
	***********
	*********/
	
	//setter
	public function sendNewEntry (Entry $obj){
		if( $this->isReadyToSend($obj) ){
			
			$log = new Log();
			
			$req = $this->_db->prepare('INSERT INTO entry SET entry_val = :val');
			
			$req->bindValue(':val', $obj->val());
			
			if($req->execute()){
			
				$log->type(201);
				$log->ip($_SERVER['REMOTE_ADDR']);
				$log->val("NO IDEA OF THE CONTENT TO PUT HERE");
				$log->entry_id( $this->_db->lastInsertId() );
				
				$this->sendNewLog($log);
				return true;
				
			}else{return false;}
			
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
	
	public function getEntryBy_id ($id, $ressource = null, $link = null){
		
		if(is_numeric($id)){
			$entry = null;
			
			$req = $this->_db->query('SELECT * FROM entry WHERE entry_id = '.$id);
			
			if($don = $req->fetch())
				$entry = new Entry($don);
			else
				return false;
			
			if($ressource = true){
				
				$entry->ressources($this->getRessourceBy_entry_id($id));
				
				if($link == true)
					$entry->links($this->getLinkBy_entry_id($id));
				
			}
			
			return $entry;
			
		}else{
			return false;
		}
		
	}
	
	public function getEntryBy_mySelf ($myRequest, $withRessources = null, $withLinks = null){
		
		if(is_string($myRequest) && !empty($myRequest)){
			
			$entrys = array();
			$req = $this->_db->query('SELECT * FROM entry WHERE '.$myRequest);
			
			while($don = $req->fetch()){
				$tempEntry = new Entry($don);
				$tempEntry->ressources( ($withRessources == true)?$this->getRessourceBy_entry_id($tempEntry->id()):null );
				$tempEntry->links( ($withRessources == true)?$this->getLinksBy_entry_id($tempEntry->id()):null );
				$entrys[] = $tempEntry;
			}
			
			return $entrys;
			
		}else{
			return false;
		}
		
	}
	
	//isEntry...
	public function isEntrySet (Entry $entry){
		
		$req = $this->_db->query('SELECT entry_id FROM entry WHERE entry_val = "'.$entry->val().'"');
		
		return ($don = $req->fetch()) ? true : false;
		
	}
	
	
	/***************************************************************************
	***************************************************************************
	**	CATEGORY PART
	***********
	*********/
	
	public function sendNewCategory (Category $obj){
		
		if( $this->isReadyToSend($obj) ){
		
			$req = $this->_db->prepare('INSERT INTO category SET category_val = :val');
			
			$req->bindValue(':val', $obj->val());
			
			$req->execute();
			
			return true;
			
		}else{
			
			return false;
		
		}
	}
	
	public function updateCategory (Category $obj){
		
		$req = $this->_db->prepare('UPDATE category SET category_val = :val WHERE category_id = :id');
		
		$req->bindValue(':id', $obj->id());
		$req->bindValue(':val', $obj->val());
		
		$req->execute();
		
	}
	
	public function getCategoryAll ($filter = null){
	
		$categorys = array();
		$reqFilter = null;
		
		//gestion du filtre
		if(is_array($filter)){
			
			$i = true;
			foreach($filter as $key => $val){
				
				if($i && is_numeric($val)){ //pour éviter un "WHERE AND ..."
					$reqFilter .= "category_id != '" . $val . "'";
					$i = false;
				}elseif(is_numeric($val))
					$reqFilter .= " AND category_id != '" . $val . "'";
					
			}
			
		}elseif(is_numeric($filter)){
			
			$reqFilter .= "category_id != '" . $filter . "'";
			
		}
		
		//requête
		$req = $this->_db->query('SELECT * FROM category' . (($filter != null)?(' WHERE ' . $reqFilter): ''));
		
		while($don = $req->fetch()){
			$categorys[] = new Category($don);
		}
		
		return $categorys;
	}
	
	public function getCategoryBy_id ($id){
		
		if(is_numeric($id)){
			
			$req = $this->_db->query('SELECT * FROM category WHERE category_id = '.$id);
			
			return ($don = $req->fetch()) ? new Category($don) : false;
			
		}else{
			return false;
		}
		
	}
	
	
	
	/**************************************************************************
	***************************************************************************
	**	LINK PART
	***********
	*********/
	
	public function sendNewLink (Link $obj){
		
		if( $this->isReadyToSend($obj) ){
		
			$req = $this->_db->prepare('INSERT INTO link SET 
				link_val = :val,
				link_from = :from,
				link_to = :to,
				link_type = :type,
				link_entry_id = :entry_id');
			
			$req->bindValue(':val', $obj->val());
			$req->bindValue(':from', $obj->from());
			$req->bindValue(':to', $obj->to());
			$req->bindValue(':type', $obj->type());
			$req->bindValue(':entry_id', $obj->entry_id());
			
			$req->execute();
		
			return true;
			
		}else{
		
			return false;
			
		}
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
	
	public function getLinkAll (){
		
		$links = array();
		
		$req = $this->_db->query('SELECT * FROM link');
		
		while($don = $req->fetch()){
			$links[] = new Link($don);
		}
		
		return $links;
		
	}
	
	public function getLinkBy_id ($id){
		
		if(is_numeric($id)){
			
			$req = $this->_db->query('SELECT * FROM link WHERE link_id = '.$id);
			
			return ($don = $req->fetch()) ? new Link($don) : false;
			
		}else{
			return false;
		}
		
	}
	
	public function getLinkBy_entry_id ($id){
		
		if(is_numeric($id)){
			
			$links = array();
			$req = $this->_db->query('SELECT * FROM link WHERE link_entry_id = '.$id);
			
			while($don = $req->fetch()){
				$links[] = new Link($don);
			}
			
			return $links;
			
		}else{
			return false;
		}
		
	}
	
	//isEntry...
	public function isLinkSet (Link $link){
		
		$req = $this->_db->query('SELECT link_id FROM link WHERE 
			link_entry_id = "'.$link->entry_id().'"'.
			'AND link_from = "'.$link->from().'"'.
			'AND link_to = "'.$link->to().'"');
		
		return ($don = $req->fetch()) ? true : false;
		
	}
	
	
	
	/**************************************************************************
	***************************************************************************
	**	LOG PART
	***********
	*********/
	
	public function sendNewLog (Log $obj){
		if( $this->isReadyToSend($obj) ){
			
			$req = $this->_db->prepare('INSERT INTO log SET 
				log_type = :type,
				log_val = :val,
				log_ip = :ip,
				log_entry_id = :entry_id
				');
			
			$req->bindValue(':val', $obj->val());
			$req->bindValue(':entry_id', $obj->entry_id());
			$req->bindValue(':ip', $obj->ip());
			$req->bindValue(':type', $obj->type());
			
			$req->execute();
			
			return true;
			
		}else{
			
			return false;
			
		}
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
	
	
	
	/**************************************************************************
	***************************************************************************
	**	RESSOURCE PART
	***********
	*********/
	
	public function sendNewRessource (Ressource $obj){
		if( $this->isReadyToSend($obj) ){
			
			$log = new Log();
			
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
			
			if($req->execute()){
			
				$log->type(202);
				$log->ip($_SERVER['REMOTE_ADDR']);
				$log->val("NO IDEA OF THE CONTENT TO PUT HERE");
				$log->entry_id($obj->entry_id());
				
				$this->sendNewLog($log);
				return true;
				
			}else{return false;}
			
		}else{
			
			return false;
			
		}
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
	
	public function getRessourceAll (){
		
		$ressources = array();
		
		$req = $this->_db->query('SELECT * FROM ressource');
		
		while($don = $req->fetch()){
			$ressources[] = new Ressource($don);
		}
		
		return $ressources;
		
	}
	
	public function getRessourceBy_id ($id){
		
		if(is_numeric($id)){
			
			$req = $this->_db->query('SELECT * FROM ressource WHERE ress_id = '.$id);
			
			return ($don = $req->fetch()) ? new Ressource($don) : false;
			
		}else{
			return false;
		}
		
	}
	
	public function getRessourceBy_entry_id ($id){
		
		if(is_numeric($id)){
			
			$ressources = array();
			$req = $this->_db->query('SELECT * FROM ressource WHERE ress_entry_id = '.$id);
			
			while($don = $req->fetch()){
				$ressources[] = new Ressource($don);
			}
			
			return $ressources;
			
		}else{
			return false;
		}
		
	}
	
	
	
	/**************************************************************************
	***************************************************************************
	**	USER PART
	***********
	*********/
	
	public function sendNewUser (User $obj){
		
		if( $this->isReadyToSend($obj) ){
			$req = $this->_db->prepare('INSERT INTO user SET 
				user_name = :name,
				user_pass = :pass,
				user_type = :type');
			
			$req->bindValue(':name', $obj->name());
			$req->bindValue(':pass', $obj->pass());
			$req->bindValue(':type', $obj->type());
			
			$req->execute();
		
			return true;
			
		}else{
			
			return false;
			
		}
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
	
	
	
	/**************************************************************************
	***************************************************************************
	**	CHECK FUNCTIONS
	***********
	*********/
	
	public function isReadyToSend ($obj){
		
		if($obj instanceof Entry){
			
			$val = $obj->val();
			return (!empty($val)) ? true : false;
		
		}elseif($obj instanceof Ressource){
		
			$val = $obj->val();
			$type = $obj->type();
			$entry_id = $obj->entry_id();
			return (!empty($val) && is_numeric($type) && is_numeric($entry_id)) ? true : false;
		
		}elseif($obj instanceof Link){
			
			$from = $obj->from();
			$to = $obj->to();
			$type = $obj->type();
			$entry_id = $obj->entry_id();
			return (is_numeric($from) && is_numeric($to) && is_numeric($type) && is_numeric($entry_id) && $obj->isPathValid()) ? true : false;
			
		}elseif($obj instanceof User){
			
			$name = $obj->name();
			$pass = $obj->pass();
			$type = $obj->type();
			return (!empty($name) && !empty($pass) && is_numeric($type)) ? true : false;
			
		}elseif($obj instanceof Category){
			
			$val = $obj->val();
			return (!empty($val)) ? true : false;
			
		}elseif($obj instanceof Log){
			
			$val = $obj->val();
			$entry_id = $obj->entry_id();
			$ip = $obj->ip();
			$type = $obj->type();
			return (!empty($val) && is_numeric($entry_id) && !empty($ip) && is_numeric($type) ) ? true : false;
			
		}else{
		
			return false;
		
		}
		
	}
}