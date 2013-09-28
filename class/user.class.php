<?php

class User 
{
	private $id,
			$name,
			$pass,
			$type;
	
	/***************
	 * CONSTRUCTOR
	 ***************/
	 
	public function __construct (array $donnees = null)
	{
		$this->hydrate($donnees);
	}
	
	public function hydrate(array $donnees = null)
	{
		if(!isset($donnees))
			return;
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
	public function name ($val = null){
		if(!empty($val) && is_string($val) && strlen($val) <= 20) $this->name = $val;
		return $this->name;
	}
	public function pass ($val = null){
		if(!empty($val) && is_string($val) && strlen($val) <= 255) $this->pass = $val;
		return $this->pass;
	}
	public function type ($val = null){
		if(isset($val) && is_numeric($val)) $this->type = $val;
		return $this->type;
	}
	
	private function set_user_id ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->id = $val;
		return $this->id;
	}
	private function set_user_name ($val = null){
		if(!empty($val) && is_string($val) && strlen($val) <= 20) $this->name = $val;
		return $this->name;
	}
	private function set_user_pass ($val = null){
		if(!empty($val) && is_string($val) && strlen($val) <= 255) $this->pass = $val;
		return $this->pass;
	}
	private function set_user_type ($val = null){
		if(isset($val) && is_numeric($val)) $this->type = $val;
		return $this->type;
	}
	
	/*********************
	 * Diverses fonctions
	 *********************/
	
	public function __toString (){
		return "id: ".$this->id()."<br/>".
			"name: ".$this->name()."<br/>".
			"pass: ".$this->pass()."<br/>".
			"type: ".$this->type();
	}
}