<?php

class Manager
{
	private $_db;
	private $_attr = null;
	
	public function __construct($db, $attribut=null)
	{
		$this->setDb($db);
		$this->_attr = DB_PREFIX;
		if($attribut != null) $this->_attr = $attribut;
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
			
			$req = $this->_db->prepare('INSERT INTO '. $this->_attr .'entry SET entry_val = :val');
			
			$req->bindValue(':val', $obj->val());
			
			if($req->execute()){
			
				//---------Création du log
				$log = new Log();
				
				$log->type(201);
				$log->val("null");
				$log->ip($_SERVER['REMOTE_ADDR']);
				
				if(isset($_SESSION['user_id'])) $log->user_id( $_SESSION['user_id'] );
				$log->entry_id( $this->_db->lastInsertId() );
				
				$this->sendNewLog($log);
				//---------Log envoyé
				
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
		
			$req = $this->_db->prepare('UPDATE '. $this->_attr .'entry SET entry_val = :val WHERE entry_id = :id');
			
			$req->bindValue(':id', $obj->id());
			$req->bindValue(':val', $obj->val());
			
			$req->execute();
		}else{
			return false;
		}
		
	}
	
	public function getEntryAll ($culumns = '*', $param = ''){
		
		$entrys = array();
		
		$req = $this->_db->query('SELECT '. $culumns .' FROM '. $this->_attr .'entry '.$param);
		
		while($don = $req->fetch()){
			$entrys[] = new Entry($don);
		}
		
		return $entrys;
		
	}
	
	public function getEntryBy_id ($id, $ressource = null, $link = null){
		
		if(is_numeric($id)){
			$entry = null;
			
			$req = $this->_db->query('SELECT * FROM '. $this->_attr .'entry WHERE entry_id = '.$id);
			
			if($don = $req->fetch())
				$entry = new Entry($don);
			else
				return false;
			
			if($ressource == true){
				
				$entry->ressources($this->getRessourceBy_entry_id($id));
				
				if($link == true)
					$entry->links($this->getLinkBy_entry_id($id));
				
			}
			
			return $entry;
			
		}else{
			return false;
		}
		
	}
	
	public function getEntryBy_mySelf ($culumns = '*', $myRequest, $withRessources = null, $withLinks = null){
		
		if(is_string($myRequest) && !empty($myRequest)){
			
			$entrys = array();
			$req = $this->_db->query('SELECT '. $culumns .' FROM '. $this->_attr .'entry WHERE '.$myRequest);
			
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
	
	public function getEntrysLength (){
		
		$req = $this->_db->query('SELECT count(entry_id) FROM '. $this->_attr .'entry');
		
		if($don = $req->fetch()){
			return $don[0];
		}
		
		// return $entrys;
		
	}
	
	public function deleteEntryBy_id ($id){
		
		if(is_numeric($id)){
			
			if($this->_db->query('DELETE FROM '. $this->_attr .'entry WHERE entry_id = '.$id)){
				
				//---------Création du log
				$log = new Log();
				
				$log->type(342);
				$log->val("null");
				$log->ip($_SERVER['REMOTE_ADDR']);
				
				if(isset($_SESSION['user_id'])) $log->user_id( $_SESSION['user_id'] );
				$log->entry_id( $id );
				
				$this->sendNewLog($log);
				//---------Log envoyé
				
				return true;
				
			}else return false;
			
		}else return false;
		
	}
	
	//isEntry...
	public function isEntrySet (Entry $entry){
		
		$req = $this->_db->query('SELECT entry_id FROM '. $this->_attr .'entry WHERE entry_val = "'.$entry->val().'"');
		
		return ($don = $req->fetch()) ? true : false;
		
	}
	
	
	/***************************************************************************
	***************************************************************************
	**	CATEGORY PART
	***********
	*********/
	
	public function sendNewCategory (Category $obj){
		
		if( $this->isReadyToSend($obj) ){
		
			$req = $this->_db->prepare('INSERT INTO '. $this->_attr .'category SET category_val = :val');
			
			$req->bindValue(':val', $obj->val());
			
			$req->execute();
			
			return true;
			
		}else{
			
			return false;
		
		}
	}
	
	public function updateCategory (Category $obj){
		
		$req = $this->_db->prepare('UPDATE '. $this->_attr .'category SET category_val = :val WHERE category_id = :id');
		
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
		$req = $this->_db->query('SELECT * FROM '. $this->_attr .'category' . (($filter != null)?(' WHERE ' . $reqFilter): ''));
		
		while($don = $req->fetch()){
			$categorys[] = new Category($don);
		}
		
		return $categorys;
	}
	
	public function getCategoryBy_id ($id){
		
		if(is_numeric($id)){
			
			$req = $this->_db->query('SELECT * FROM '. $this->_attr .'category WHERE category_id = '.$id);
			
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
		
			$req = $this->_db->prepare('INSERT INTO '. $this->_attr .'link SET 
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
			
			if($req->execute()){
				
				//---------Création du log
				$log = new Log();
				
				$log->type(321);
				$log->val("null");
				$log->ip($_SERVER['REMOTE_ADDR']);
				
				if(isset($_SESSION['user_id'])) $log->user_id( $_SESSION['user_id'] );
				$log->entry_id( $obj->entry_id() );
				
				$this->sendNewLog($log);
				//---------Log envoyé
				
				return true;
			
			}else return false;
			
		}else{
		
			return false;
			
		}
	}
	
	public function updateLink (Link $obj){
		
		$req = $this->_db->prepare('UPDATE '. $this->_attr .'link SET
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
		
		$req = $this->_db->query('SELECT * FROM '. $this->_attr .'link');
		
		while($don = $req->fetch()){
			$links[] = new Link($don);
		}
		
		return $links;
		
	}
	
	public function getLinkBy_id ($id){
		
		if(is_numeric($id)){
			
			$req = $this->_db->query('SELECT * FROM '. $this->_attr .'link WHERE link_id = '.$id);
			
			return ($don = $req->fetch()) ? new Link($don) : false;
			
		}else{
			return false;
		}
		
	}
	
	public function getLinkBy_entry_id ($id){
		
		if(is_numeric($id)){
			
			$links = array();
			$req = $this->_db->query('SELECT * FROM '. $this->_attr .'link WHERE link_entry_id = '.$id);
			
			while($don = $req->fetch()){
				$links[] = new Link($don);
			}
			
			return $links;
			
		}else{
			return false;
		}
		
	}
	
	
	public function deleteLinkBy_entry_id ($id){
		
		if(is_numeric($id)){
			
			return ($this->_db->query('DELETE FROM '. $this->_attr .'link WHERE link_entry_id = '.$id)) ? true : false;
			
		}else{
			return false;
		}
		
	}
	
	
	public function deleteLinkBy_ress_id ($id){
		
		if(is_numeric($id)){
			
			return ($this->_db->query('DELETE FROM '. $this->_attr .'link WHERE link_from = "'.$id.'" OR link_to = "'.$id.'"')) ? true : false;
			
		}else{
			return false;
		}
		
	}
	
	
	//isEntry...
	public function isLinkSet (Link $link){
		
		$req = $this->_db->query('SELECT link_id FROM '. $this->_attr .'link WHERE 
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
			
			$user_id = $obj->user_id();
			
			$req = $this->_db->prepare('INSERT INTO '. $this->_attr .'log SET 
				log_type = :type,
				log_val = :val,
				log_ip = :ip,
				log_entry_id = :entry_id'.
				((!empty($user_id)) ? ', log_user_id = :user_id' : '')
				);
			
			$req->bindValue(':val', $obj->val());
			$req->bindValue(':entry_id', $obj->entry_id());
			$req->bindValue(':ip', $obj->ip());
			$req->bindValue(':type', $obj->type());
			if(!empty($user_id)) $req->bindValue(':user_id', $obj->user_id());
			
			$req->execute();
			
			return true;
			
		}else{
			
			return false;
			
		}
	}
	
	public function updateLog (Log $obj){
		
		$req = $this->_db->prepare('UPDATE '. $this->_attr .'log SET 
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
	
	public function getLogAll ($culumns = '*'){
		
		$logs = array();
		
		$req = $this->_db->query('SELECT '. $culumns .' FROM '. $this->_attr .'log');
		
		while($don = $req->fetch()){
			$logs[] = new Log($don);
		}
		
		return $logs;
		
	}
	
	public function getLogBy_type ($type, $culumns = '*'){
		
		if(is_numeric($type)){
			$logs = array();
			
			$req = $this->_db->query('SELECT '. $culumns .' FROM '. $this->_attr .'log WHERE log_type = "'. $type .'" ORDER BY log_id DESC');
			
			while($don = $req->fetch()){
				$logs[] = new Log($don);
			}
			
			return $logs;
		}else{
			return false;
		}
		
	}
	
	public function getLogBy_id ($id, $culumns = '*'){
		
		if(is_numeric($id)){
			$log = null;
			
			$req = $this->_db->query('SELECT '. $culumns .' FROM '. $this->_attr .'log WHERE log_id = "'. $id .'" ORDER BY log_id DESC');
			
			if($don = $req->fetch()){
				$log = new Log($don);
			}
			
			return $log;
		}else{
			return false;
		}
		
	}
	
	public function getLastTrendChange ($ress){
		
		if($ress instanceof Ressource){
			$req = $this->_db->query("SELECT log_id, log_type FROM ". $this->_attr ."log WHERE 
				log_type >= 301 AND log_type <= 302 AND log_ip = '". $_SERVER['REMOTE_ADDR'] ."' AND log_entry_id = '". $ress->entry_id() ."' AND log_val = 'id$". $ress->id() ."' ORDER BY log_id DESC LIMIT 0,2");
			
			$changes = array();
			while($don = $req->fetch()){
				$changes[] = $don['log_type'];
			}
			
			return $changes;
		}else return false;
		
	}
	
	public function isRessourceAlerted ($ress){
		
		if($ress instanceof Ressource){
			$req = $this->_db->query("SELECT log_id FROM ". $this->_attr ."log WHERE 
				log_type = 303 AND log_ip = '". $_SERVER['REMOTE_ADDR'] ."' AND log_entry_id = '". $ress->entry_id() ."' AND log_val = 'id$". $ress->id() ."' ORDER BY log_id DESC LIMIT 0,1");
			
			return ($don = $req->fetch()) ? true : false;
		}else return false;
		
	}
	
	
	
	/**************************************************************************
	***************************************************************************
	**	RESSOURCE PART
	***********
	*********/
	
	public function sendNewRessource (Ressource $obj){
		if( $this->isReadyToSend($obj) ){
			
			$log = new Log();
			
			$sql = 'INSERT INTO '. $this->_attr .'ressource SET 
				ress_val = :val,
				ress_type = :type,'.
				(($obj->trend() != null) ? 'ress_trend = :trend,' : '' ).
				(($obj->alert() != null) ? 'ress_alert = :alert,' : '' ).
				(($obj->titre() != null) ? 'ress_titre = :titre,' : '' ).
				(($obj->category_id() != null) ? 'ress_category_id = :category_id,' : '' ).
				'ress_entry_id = :entry_id';
			
			$req = $this->_db->prepare($sql);
			
			$req->bindValue(':val', $obj->val());
			$req->bindValue(':type', $obj->type());
			if($obj->trend() != null) $req->bindValue(':trend', $obj->trend());
			if($obj->alert() != null) $req->bindValue(':alert', $obj->alert());
			if($obj->titre() != null) $req->bindValue(':titre', $obj->titre());
			if($obj->category_id() != null) $req->bindValue(':category_id', $obj->category_id());
			$req->bindValue(':entry_id', $obj->entry_id());
			
			if($req->execute()){
				
				//---------Création du log
				$log = new Log();
				
				$log->type(304);
				$log->val("null");
				$log->ip($_SERVER['REMOTE_ADDR']);
				
				if(isset($_SESSION['user_id'])) $log->user_id( $_SESSION['user_id'] );
				$log->entry_id( $obj->entry_id() );
				
				$this->sendNewLog($log);
				//---------Log envoyé
				
				return true;
				
			}else return false;
			
		}else{
			
			return false;
			
		}
	}
	
	public function updateRessource (Ressource $obj){
		
		$req = $this->_db->prepare('UPDATE '. $this->_attr .'ressource SET 
			ress_val = :val,
			ress_type = :type,
			ress_trend = :trend,
			ress_alert = :alert,
			ress_titre = :titre,
			ress_entry_id = :entry_id,
			ress_category_id = :category_id
			WHERE ress_id = :id');
		
		$req->bindValue(':val', $obj->val());
		$req->bindValue(':type', $obj->type());
		$req->bindValue(':trend', $obj->trend());
		$req->bindValue(':alert', $obj->alert());
		$req->bindValue(':titre', $obj->titre());
		$req->bindValue(':entry_id', $obj->entry_id());
		$req->bindValue(':category_id', $obj->category_id());
		$req->bindValue(':id', $obj->id());
		
		return ($req->execute()) ? true : false;
		
	}
	
	public function updateRessource_trend ($id, $offset){
		
		if(is_numeric($id) && is_numeric($offset) && $id > 0){
			
			$req = $this->_db->prepare('UPDATE '. $this->_attr .'ressource SET 
			ress_trend = :offset + ress_trend 
			WHERE ress_id = :id');
		
			$req->bindValue(':offset', $offset);
			$req->bindValue(':id', $id);
			
			return ($req->execute()) ? true : false;
			
		}else return false;
		
	}
	
	public function getRessourceAll ($column = '*'){
		
		$ressources = array();
		
		$req = $this->_db->query('SELECT '. $column .' FROM '. $this->_attr .'ressource');
		
		while($don = $req->fetch()){
			$ressources[] = new Ressource($don);
		}
		
		return $ressources;
		
	}
	
	public function getRessourceBy_id ($id){
		
		if(is_numeric($id)){
			
			$req = $this->_db->query('SELECT * FROM '. $this->_attr .'ressource WHERE ress_id = '.$id);
			
			return ($don = $req->fetch()) ? new Ressource($don) : false;
			
		}else{
			return false;
		}
		
	}
	
	public function getRessourceBy_entry_id ($id){
		
		if(is_numeric($id)){
			
			$ressources = array();
			$req = $this->_db->query('SELECT * FROM '. $this->_attr .'ressource WHERE ress_entry_id = '.$id." ORDER BY ress_trend ASC");
			
			while($don = $req->fetch()){
				$ressources[] = new Ressource($don);
			}
			
			return $ressources;
			
		}else{
			return false;
		}
		
	}
	
	public function getRessourcesLength (){
		
		$req = $this->_db->query('SELECT count(ress_id) FROM '. $this->_attr .'ressource');
		
		if($don = $req->fetch()){
			return $don[0];
		}
		
		// return $entrys;
		
	}
	
	
	public function deleteRessourceBy_id ($id){
		
		if(is_numeric($id)){
			
			if($this->_db->query('DELETE FROM '. $this->_attr .'ressource WHERE ress_id = '.$id)){
				
				//---------Création du log
				$log = new Log();
				
				$log->type(305);
				$log->val("null");
				$log->ip($_SERVER['REMOTE_ADDR']);
				
				if(isset($_SESSION['user_id'])) $log->user_id( $_SESSION['user_id'] );
				$log->entry_id( $id );
				
				$this->sendNewLog($log);
				//---------Log envoyé
				
				return true;
				
			}else return false;
			
		}else{
			return false;
		}
		
	}
	
	
	public function deleteRessourceBy_entry_id ($id){
		
		if(is_numeric($id)){
			
			return ($this->_db->query('DELETE FROM '. $this->_attr .'ressource WHERE ress_entry_id = '.$id)) ? true : false;
			
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
		
			$req = $this->_db->prepare('INSERT INTO lexique_admin_user SET 
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
		
		$req = $this->_db->prepare('UPDATE lexique_admin_user SET 
			user_name = :name,
			user_pass = :pass,
			user_type = :type
			WHERE user_id = :id');
		
		$req->bindValue(':name', $obj->name());
		$req->bindValue(':pass', $obj->pass());
		$req->bindValue(':type', $obj->type());
		$req->bindValue(':id', $obj->id());
		
		return ($req->execute()) ? true : false ;
		
	}
	
	public function loginUser (User $obj){
		$name = $obj->name();
		$pass = $obj->pass();
		
		if( !empty($name) && !empty($pass) ){
			$req = $this->_db->query('SELECT * FROM lexique_admin_user WHERE user_name="'. $name .'" AND user_pass="'. $pass .'"');
			
			if($don = $req->fetch()){
				
				$tab = array(
					'name' => $don['user_name'],
					'type' => $don['user_type'],
					'id' => $don['user_id'],
					'token' => time()
				);
				
				return $tab;
				
			}else{
				return false;
			}
			
		}else{
			return false;
		}
		
	}
	
	public function getUserBy_id ($id){
	
		if(is_numeric($id) && $id > 0){
		
			$req = $this->_db->query('SELECT * FROM lexique_admin_user WHERE user_id='.$id);
			
			if($don = $req->fetch()){
				
				return new User($don);
				
			}else
				return false;
		}else return false;
		
	}
	
	public function getUserAll (){
	
		$req = $this->_db->query('SELECT * FROM lexique_admin_user');
		$tab = array();
		
		while($don = $req->fetch()){
			
			$tab[] = new User($don);
			
			
		}
		return $tab;
		
	}
	
	public function removeUserBy_id ($id){
	
		if(is_numeric($id)){
			return ($this->_db->query('DELETE FROM lexique_admin_user WHERE user_id="'. $id .'"')) ? true : false;
		}
		
	}
	
	
	
	
	/**************************************************************************
	***************************************************************************
	**	LEXIQUE PART
	***********
	*********/
	
	public function createLexique ($name, $attr){
		
		if( is_string($name) && is_string($attr) && strlen($name) <= 30 && strlen($attr) <= 10 ){
		
			$req = $this->_db->prepare('INSERT INTO lexique_admin_references SET 
				lexique_name = :name,
				lexique_attr = :attr');
			
			$req->bindValue(':name', $name);
			$req->bindValue(':attr', $attr);
			
			return ($req->execute()) ? true : false;
		
		}else{
			
			return false;
			
		}
	}
	
	public function lexiqueToggleActivity ($id){
		
		if( is_numeric($id) && $id > 0 ){
		
			$req = $this->_db->query('SELECT lexique_statut FROM lexique_admin_references WHERE 
				lexique_id = "'. $id .'"');
				
				if($don = $req->fetch()){
					
					$req2 = $this->_db->prepare('UPDATE lexique_admin_references SET lexique_statut = :statut WHERE lexique_id = :id');
					$req2->bindValue(':statut', ($don['lexique_statut'] == 1) ? 0 : 1);
					$req2->bindValue(':id', $id);
					
					//---------Création du log
					$log = new Log();
					
					$log->type( ($don['lexique_statut'] == 1) ? 364 : 363 );
					$log->val('id$'.$id);
					$log->ip($_SERVER['REMOTE_ADDR']);
					
					if(isset($_SESSION['user_id'])) $log->user_id( $_SESSION['user_id'] );
					
					$this->sendNewLog($log);
					//---------Log envoyé
					
					return ($req2->execute()) ? true : false;
					
				}else return false;
			
		
		}else return false;
	}
	
	public function getLexiquesAll (){
		
		$req = $this->_db->query('SELECT * FROM lexique_admin_references');
		
		$tab = array();
		
		while($don = $req->fetch()){
		
			$temp = array();
			$temp['id'] = $don['lexique_id'];
			$temp['name'] = $don['lexique_name'];
			$temp['attr'] = $don['lexique_attr'];
			$temp['statut'] = $don['lexique_statut'];
			
			$tab[] = $temp;
			
		}
		
		return $tab;
		
	}
	
	public function getLexiquesBy_id ($id){
		
		if( is_numeric($id) && $id > 0){
			
			$req = $this->_db->query('SELECT * FROM lexique_admin_references WHERE lexique_id = "'. $id .'"');
			
			$tab = array();
			
			if($don = $req->fetch()){
			
				$tab['id'] = $don['lexique_id'];
				$tab['name'] = $don['lexique_name'];
				$tab['attr'] = $don['lexique_attr'];
				$tab['statut'] = $don['lexique_statut'];
				
				return $tab;
				
			}else return false;
			
		}else return false;
	}
	
	public function isLexiqueExist ($name, $attr){
		
		if( is_string($name) && is_string($attr) && strlen($name) <= 30 && strlen($attr) <= 10 ){
		
			$req = $this->_db->query('SELECT lexique_id FROM lexique_admin_references WHERE 
				lexique_name = "'. $name .'" OR
				lexique_attr = "'. $attr .'"');
			
			$req->bindValue(':name', $name);
			$req->bindValue(':attr', $attr);
			
			return ($don = $req->fetch()) ? true : false;
		
		}else{
			
			return false;
			
		}
		
	}
	
	public function deleteLexique ($id){
		
		if( is_numeric($id) && $id > 0 ){
		
			$req = $this->_db->prepare('DELETE FROM lexique_admin_references WHERE 
				lexique_id = :id');
			
			$req->bindValue(':id', $id);
			
			return ($req->execute()) ? true : false;
		
		}else return false;
		
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
			// $entry_id = $obj->entry_id();
			$ip = $obj->ip();
			$type = $obj->type();
			return (!empty($val) && !empty($ip) && is_numeric($type) ) ? true : false;
			
		}else{
		
			return false;
		
		}
		
	}
}