<?php

class Ressource 
{
	private $id,
			$val,
			$create_date,
			$trend,
			$type,
			$entry_id,
			$category_id,
			$alert;
	
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
			  $this->$method( stripcslashes(htmlspecialchars_decode($value)) );
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
		if(!empty($val) && is_string($val)) $this->val = $val;
		return $this->val;
	}
	public function create_date ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->create_date = $val;
		return $this->create_date;
	}
	public function type ($val = null){
		if(isset($val) && is_numeric($val)) $this->type = $val;
		return $this->type;
	}
	public function trend ($val = null){
		if(isset($val) && is_numeric($val)) $this->trend = $val;
		return $this->trend;
	}
	public function alert ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->alert = $val;
		return $this->alert;
	}
	public function entry_id ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->entry_id = $val;
		return $this->entry_id;
	}
	public function category_id ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->category_id = $val;
		return $this->category_id;
	}
	
	//fonctions utiles seulement pour la construction de la classe
	private function set_ress_id ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->id = $val;
		return $this->id;
	}
	private function set_ress_val ($val = null){
		if(!empty($val) && is_string($val) && strlen($val) <= 255) $this->val = $val;
		return $this->val;
	}
	private function set_ress_create_date ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->create_date = $val;
		elseif(isset($val) && is_string($val)) $this->create_date = $val;
		return $this->create_date;
	}
	private function set_ress_type ($val = null){
		if(isset($val) && is_numeric($val)) $this->type = $val;
		return $this->type;
	}
	private function set_ress_trend ($val = null){
		if(isset($val) && is_numeric($val)) $this->trend = $val;
		return $this->trend;
	}
	private function set_ress_alert ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->alert = $val;
		return $this->alert;
	}
	public function set_ress_entry_id ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->entry_id = $val;
		return $this->entry_id;
	}
	public function set_ress_category_id ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->category_id = $val;
		return $this->category_id;
	}
	
	/*********************
	 * Diverses fonctions
	 *********************/
	
	public function __toString (){
		return "id: ".$this->id()."<br/>".
			"val: ".$this->val()."<br/>".
			"create_date: ".$this->create_date()."<br/>".
			"type: ".$this->type()."<br/>".
			"trend: ".$this->trend()."<br/>".
			"alert: ".$this->alert()."<br/>".
			"entry_id: ".$this->entry_id()."<br/>".
			"category_id: ".$this->category_id();
	}
	
	public function getArray (){
		
		$dataRessource= array(
			"id" => $this->id(),
			"val" => $this->val(),
			"create_date" => $this->create_date(),
			"type" => $this->type(),
			"trend" => $this->trend(),
			"alert" => $this->alert(),
			"entry_id" => $this->entry_id(),
			"category_id" => $this->category_id()
		);
		
		return $dataRessource;
		
	}
	
}