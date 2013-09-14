<?php

class Link 
{
	private $id,
			$val,
			$create_date,
			$from,
			$to,
			$entry_id,
			$type;
	
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
		if(!empty($val) && is_string($val) && strlen($val) <= 50) $this->val = $val;
		return $this->val;
	}
	public function create_date ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->create_date = $val;
		return $this->create_date;
	}
	public function from ($val = null){
		if(isset($val) && is_numeric($val) && $val > 0) $this->from = $val;
		return $this->from;
	}
	public function to ($val = null){
		if(isset($val) && is_numeric($val) && $val > 0) $this->to = $val;
		return $this->to;
	}
	public function type ($val = null){
		if(isset($val) && is_numeric($val)) $this->type = $val;
		return $this->type;
	}
	public function entry_id ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->entry_id = $val;
		return $this->entry_id;
	}
	
	//fonctions utiles seulement pour la construction de la classe
	private function set_link_id ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->id = $val;
		return $this->id;
	}
	private function set_link_val ($val = null){
		if(!empty($val) && is_string($val) && strlen($val) <= 50) $this->val = $val;
		return $this->val;
	}
	private function set_link_create_date ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->create_date = $val;
		return $this->create_date;
	}
	private function set_link_from ($val = null){
		if(isset($val) && is_numeric($val) && $val > 0) $this->from = $val;
		return $this->from;
	}
	private function set_link_to ($val = null){
		if(isset($val) && is_numeric($val) && $val > 0) $this->to = $val;
		return $this->to;
	}
	private function set_link_type ($val = null){
		if(isset($val) && is_numeric($val)) $this->type = $val;
		return $this->type;
	}
	public function set_link_entry_id ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->entry_id = $val;
		return $this->entry_id;
	}
	
	/*********************
	 * Diverses fonctions
	 *********************/
	
	public function isPathValid (){
		
		return ( $this->from == $this->to ) ? false : true;
		
	}
	
	public function __toString (){
		return "id: ".$this->id()."<br/>".
			"val: ".$this->val()."<br/>".
			"create_date: ".$this->create_date()."<br/>".
			"from: ".$this->from()."<br/>".
			"to: ".$this->to()."<br/>".
			"type: ".$this->type()."<br/>".
			"entry_id: ".$this->entry_id();
	}
	
}