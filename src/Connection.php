<?php

namespace Chay22\RecSelMeter;

abstract class Connection
{
	/**
	 * URL to execute
	 * 
	 * @var $url
	 */
	public $url;

	/**
	 * Contains curl_init() if success
	 *
	 * @var $init
	 */
	protected $init = false;

	/**
	 *  Additional cURL options
	 *  
	 *  @var $options
	 */
	public $options = [];

	/**
	 * @var $header
	 */
	public $header = [];

	/**
	 * @var $content
	 */
	public $content;

	/**
	 * @param  string  $url  Store URL
	 * /
	function __construct($url)
	{	
		if (!function_exists('curl_version')) {
			throw new \Exception('This library needs cURL to run!');
		}
		$this->init = $this->init($url);
		$this->connection();
	}

	/**
	 * Set additional curl_setopt_array() options
	 * 
	 * @param 	array 	$options 	cURL Options
	 * @see http://php.net/manual/en/function.curl-setopt.php
	 * @return array
	 */
	public function setOptions($options = [])
	{
		return $this->options = $options;
	}

	/**
	 * Running the curl session
	 * 
	 * @return void
	 */
	public function connection()
	{
		
		$ch = $this->init;
        	curl_setopt_array($ch, ($this->options() + $this->options));
        	if (!$this->content = curl_exec($ch)){
        		throw new \Exception(
        			'CURL ERROR! Code: ' . curl_errno($ch) .
        		'	Message: ' . curl_error($ch)
        		);
        	}
        	$this->header = curl_getinfo($ch);
        	curl_close($ch);
	}

	/**
	 * Get result of both header and content from curl
	 * 
	 * @return array
	 */
	public function result()
	{
		return [
			'headers', $this->connection['headers'],
			'header', $this->connection['header'],
			'content', $this->connection['content'],
		       ];
	}

	/**
	 * Set cURL options
	 * 
	 * @return array
	 */
	protected function options()
	{
		return [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
            		CURLOPT_ENCODING       => '',
            		CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
		       ];
	}

	/**
	 * Validate URL and start cURL session;
	 *
	 * @param  string  $url  Store URL
	 */
	protected function init($url)
	{
		//Grab url id in case $url is not valid as required url
		$id = $url;
		if (strpos($url, 'product') !== false) {
    			$id = explode('product',$url);
    			$id = end($id);
    			$id = explode('/', $id)[1];
		}
		
		//Set the $id as required url
		$this->url = 'http://fjb.kaskus.co.id/product/' . $id;
		if (!curl_init($url)) {
			throw new \Exception('Can\'t start cURL session.');
		}
		return curl_init($url);
	}
}
