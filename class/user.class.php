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
	 
	public function __construct (array $donnees)
	{
		$this->hydrate($donnees);
	}
	
	public function hydrate(array $donnees)
	{
		foreach ($donnees as $key => $value)
		{
			$method = preg_replace("#^user_#", "", $key);
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
	};
	public function name ($val = null){
		if(!empty($val) && is_string($val) && strlen($val) <= 20) $this->name = $val;
		return $this->name;
	};
	public function pass ($val = null){
		if(!empty($val) && is_string($val) && strlen($val) <= 255) $this->pass = $val;
		return $this->pass;
	};
	public function type ($val = null){
		if(isset($val) && is_numeric($val)) $this->type = $val;
		return $this->type;
	};
	
}