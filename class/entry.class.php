<?php

class Entry 
{
	private $id,
			$val,
			$create_date;
	
	public  $ressources,
			$links;
	
	/***************
	 * CONSTRUCTOR
	 ***************/
	 
	public function __construct (array $donnees)
	{
		$this->hydrate($donnees);
	}
	
	public function hydrate(array $donnees)
	{
		foreach ($donnees as $key => $value)
		{
			$method = 'set_'.$key;
			if (method_exists($this, $method))
			{
			  $this->$method($value);
			}
		}
	}
	
	/********************
	 * getters & setters
	 ********************/
	
	public function id ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->id = $val;
		return $this->id;
	}
	public function val ($val = null){
		if(isset($val) && is_string($val) && strlen($val) <= 50) $this->val = $val;
		return $this->val;
	}
	public function create_date ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->create_date = $val;
		return $this->create_date;
	}
	public function ressources ($val = null){
		if( is_array($val) ) $this->ressources = $val;
		return $this->ressources;
	}
	public function links ($val = null){
		if( is_array($val) ) $this->links = $val;
		return $this->links;
	}
	
	public function addRessource (Ressource $obj){
		$this->ressources[] = $obj;
	}
	public function getRessource ($val, $regex = null){
		if( is_int($val) ) return $this->ressources[$val];
		elseif( is_string($val) ){
		
			$results = array();
			foreach($this->ressources as $ress){
			
				if($regex == true){
					if(preg_match($val, $ress->val()))
						$results[] = $ress;
				}else{
					if($val == $ress->val()) return $ress;
				}
				
			}
			return $results;
			
		}else{
			return false;
		}
	}
	
	//fonctions utiles seulement pour la construction de la classe
	private function set_entry_id ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->id = $val;
		return $this->id;
	}
	private function set_entry_val ($val = null){
		if(isset($val) && is_string($val) && strlen($val) <= 50) $this->val = $val;
		return $this->val;
	}
	private function set_entry_create_date ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->create_date = $val;
		return $this->create_date;
	}
	
	/*********************
	 * Diverses fonctions
	 *********************/
	
	public function __toString (){
		return "id: ".$this->id()."<br/>".
			"val: ".$this->val()."<br/>".
			"create_date: ".$this->create_date();
	}
	
	/*
	
		TODO permettre l'ajout de ressources
		TODO système de navigation dans les ressources (recherche, fetch, reset/place cursor, ...)
		TODO développer le manager global gérant tout d'un coup (moins chiant qu'un manger par classe)
	
	*/
	
}