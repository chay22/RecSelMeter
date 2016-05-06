<?php

namespace Chay22\RecSelMeter;


class Config
{
	/**
	 * @var array $rank
	 */
	private $rank = [
			 'auto banned'		=> -100,
			 'kaskus addict'	=> 2,
			 'kaskus maniac'	=> 3,
			 'kaskus geek'		=> 7,
			 'kaskus freak'		=> 10,
			 'made in kaskus'	=> 15,
			 'kaskus plus'		=> 15,
			 'reg. leader'		=> 15,
			 'moderator'		=> 15,
			 'kaskus online bazaar'	=> 200,
			];

	/**
	 * @var array $feedback
	 */
	private $feedback = [10];

	/**
	 * @var array $feedbackPercent
	 */
	private $feedbackPercent = [10];

	/**
	 * @var string $accountAge
	 */
	private $accountAge = 5;

	/**
	 * @var array $storeActive
	 */
	private $storeActive = [
				7 => 1,
				3 => 5,
				1 => 8,
				0 => 10,
			       ];
	/**
	 * @var string $imageCount
	 */				  
	private $imageCount = 1;

	/**
	 * @var array $sold
	 */
	private $sold = [
			 0  => 0,
			 1  => 20,
			 5  => 50,
			 10 => 100,
			];
	/**
	 * @var string $cod
	 */
	private $cod = 10;

	/**
	 * Return set of configuration data (properties)
	 * 
	 * @return array
	 */
	public function data()
	{
		return get_object_vars($this);
	}

	/**
	 * Create/add/modify chosen property value
	 *
	 * @see ::create()
	 * @see ::add()
	 * @see ::set()
	 */
	public function __call($name, $args = [])
	{	
		//Throw error if property is not found
		if (!$args = $this->validateArgs($args)) {
			throw new \Exception('Parameters need to be an array!');
		}

		//Change ::new() to ::create() since it's a reserved word
		if (stripos($name, 'new') !== false) {
			$name = str_replace('new', 'create', $name);
		}
		$method = $this->getMethod($name);
		$property = explode($method, $name);
		$property = end($property);
		$config['name'] = $this->getArgs($property);
		$config['value'] = $args[0];

		return $this->{$method}($config);
	}

	private function validateArgs($args)
	{
		if (!is_array($args)) {
			return false;
		}

		foreach ($args[0] as $value) {
			if (!is_int($value)) {
				return false;
			}
		}

		return array_change_key_case($args, CASE_LOWER);
	}

	private function getMethod($name)
	{
		foreach(get_class_methods($this) as $method) {
			if(stripos($name, $method) !== false) {
				return $method;
			}
		}
		
		throw new \Exception('Method not found');
	}

	private function getArgs($name)
	{
		foreach(get_object_vars($this) as $property => $value) {
			if (stripos($property, $name) !== false) {
				return $property;
			}
		}
		
		throw new \Exception('Property not found');
	}

	/**
	 * Add new key and value for chosen config (property)
	 * 
	 * @param 	array 	$config  name of config and it's key and value
	 * @return void
	 */
	private function add($config = [])
	{
		$this->{$config['name']} += $config['value'];
	}

	/**
	 * Overwrite default configuration (key and) value
	 *
	 * @uses 	::new()
	 * @param 	array 	$config  name of config and it's key and value
	 * @return 	void
	 */
	private function create($config = [])
	{
		$this->{$config['name']} = $config['value'];
	}

	/**
	 * Modify value of configuration key
	 * 
	 * @param 	array 	$config  name of config and it's key and value
	 * @return void
	 */
	private function set($config = [])
	{	
		$this->{$config['name']} = $config['value'] + $this->{$config['name']};
	}
}
