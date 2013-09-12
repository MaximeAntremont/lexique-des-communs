<?php

class Link 
{
	private $id,
			$val,
			$create_date,
			$from,
			$to,
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
	};
	public function val ($val = null){
		if(!empty($val) && is_string($val) && strlen($val) <= 50) $this->val = $val;
		return $this->val;
	};
	public function create_date ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->create_date = $val;
		return $this->create_date;
	};
	public function from ($val = null){
		if(isset($val) && is_numeric($val) && $val > 0) $this->from = $val;
		return $this->from;
	};
	public function to ($val = null){
		if(isset($val) && is_numeric($val) && $val > 0) $this->to = $val;
		return $this->to;
	};
	public function type ($val = null){
		if(isset($val) && is_numeric($val)) $this->type = $val;
		return $this->type;
	};
	
	//fonctions utiles seulement pour la construction de la classe
	private function set_link_id ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->id = $val;
		return $this->id;
	};
	private function set_link_val ($val = null){
		if(!empty($val) && is_string($val) && strlen($val) <= 50) $this->val = $val;
		return $this->val;
	};
	private function set_link_create_date ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->create_date = $val;
		return $this->create_date;
	};
	private function set_link_from ($val = null){
		if(isset($val) && is_numeric($val) && $val > 0) $this->from = $val;
		return $this->from;
	};
	private function set_link_to ($val = null){
		if(isset($val) && is_numeric($val) && $val > 0) $this->to = $val;
		return $this->to;
	};
	private function set_link_type ($val = null){
		if(isset($val) && is_numeric($val)) $this->type = $val;
		return $this->type;
	};
	
}