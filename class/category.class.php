<?php

class Category 
{
	private $id,
			$val,
			$create_date;
	
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
	};
	public function val ($val = null){
		if(isset($val) && is_string($val) && strlen($val) <= 25) $this->val = $val;
		return $this->val;
	};
	public function create_date ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->create_date = $val;
		return $this->create_date;
	};
	
	//fonctions utiles seulement pour la construction de la classe
	private function set_category_id ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->id = $val;
		return $this->id;
	};
	private function set_category_val ($val = null){
		if(isset($val) && is_string($val) && strlen($val) <= 25) $this->val = $val;
		return $this->val;
	};
	private function set_category_create_date ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->create_date = $val;
		return $this->create_date;
	};
	
}