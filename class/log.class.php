<?php

class Log 
{
	private $id,
			$val,
			$create_date,
			$ip,
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
		if(!empty($val) && is_string($val) && strlen($val) <= 5000) $this->val = $val;
		return $this->val;
	};
	public function entry_id ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->entry_id = $val;
		return $this->entry_id;
	};
	public function create_date ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->create_date = $val;
		return $this->create_date;
	};
	public function ip ($val = null){
		if(!empty($val) && is_string($val) && strlen($val) <= 255) $this->ip = $val;
		return $this->ip;
	};
	public function type ($val = null){
		if(isset($val) && is_numeric($val)) $this->type = $val;
		return $this->type;
	};
	
	//fonctions utiles seulement pour la construction de la classe
	private function set_log_id ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->id = $val;
		return $this->id;
	};
	private function set_log_val ($val = null){
		if(!empty($val) && is_string($val) && strlen($val) <= 5000) $this->val = $val;
		return $this->val;
	};
	private function set_log_entry_id ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->entry_id = $val;
		return $this->entry_id;
	};
	private function set_log_create_date ($val = null){
		if(isset($val) && is_numeric($val) && $val >= 0) $this->create_date = $val;
		return $this->create_date;
	};
	private function set_log_ip ($val = null){
		if(!empty($val) && is_string($val) && strlen($val) <= 255) $this->ip = $val;
		return $this->ip;
	};
	private function set_log_type ($val = null){
		if(isset($val) && is_numeric($val)) $this->type = $val;
		return $this->type;
	};
	
}