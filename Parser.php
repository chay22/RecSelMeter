<?php

namespace Chay22\RecSelMeter;

use chay22\RecSelMeter\Connection;


class Parser extends Connection
{
	/**
	 * @var $xpath
	 */
	protected $xpath;

	/**
	 * Retrieve amount product sold
	 *
	 * @var $sold
	 */
	public $sold;

	/**
	 * @var $cod
	 */
	public $cod = false;

	/**
	 * Latest bump attempt time
	 *
	 * @var $bump
	 */
	public $bump;

	function __construct($url)
	{
		parent::__construct($url);
		date_default_timezone_set('Asia/Jakarta');
		libxml_use_internal_errors(true);

		if ($this->header['http_code'] != 200 ) {
			throw new \Exception("URL not found!");
		}

		$this->xpath = $this->document(new \DOMDocument); 
	}

	/**
	 * Creates DOMXPath object from content
	 * 
	 * @return object
	 */
	protected function document(\DOMDocument $doc)
	{
		$doc->loadHTML($this->content);
		return new \DOMXPath($doc);
	}

	/**
	 * Find element from content with xpath expression
	 * 
	 * @param 	string 	$query 	xpath query expression
	 * @return 	bool(false)|DOMNodeList
	 * @see http://php.net/manual/en/domxpath.query.php
	 */
	public function find($query)
	{
		return $this->xpath->query($query);
	}

	/**
	 * Fetch all result into array
	 * 
	 * @return array
	 */
	public function fetch()
	{
		$product = $this->product();

		return [
				'username'			=>	$this->username(),
				'user_id'			=>	$this->userID(),
				'rank'				=>	$this->rank(),
				'feedback'			=>	$this->feedback(),
				'feedback_percent'	=>	$this->feedbackPercent(),
				'join_date'			=>	$this->joinDate(),
				'verified'			=>	$this->verifiedSeller(),
				'published_at'		=> 	$this->threadPublished(),
				'image_count'		=>	$this->countImage(),
				'sold'				=>	$product['sold'],
				'cod'				=>	$product['cod'],
				'last_bump'			=>	$product['bump'],
			   ];
	}

	/**
	 * Get username
	 * 
	 * @return string
	 */
	public function username()
	{
		return $this->find('//meta[@name="author"]')
				->item(0)
				->getAttribute('content');
	}

	/**
	 * Get user id
	 * 
	 * @return int
	 */
	public function userID()
	{
		$query = $this->find(
					'//div[contains(@class,"seller-detail-info")]' . 
					'/span[contains(@class,"username")]/a'
				)->item(0)
				 ->getAttribute('href');
		$query = explode('/', $query);
		$query = end($query);

		return $query + 0;
	}

	/**
	 * Amount of image of thread
	 * 
	 * @return int
	 */
	public function countImage()
	{
		return $this->find(
				'//div[contains(@id,"carousel-thumb")]' .
				'/div[contains(@class,"thumbnail")]'
			)->length;
	}

	/**
	 * Get user rank
	 * 
	 * @return string
	 */
	public function rank()
	{
		return $this->find(
				'//div[contains(@class,"seller-detail-info")]' . 
				'/span[contains(@class,"rank")]'
			)->item(0)
			 ->nodeValue;
	}

	/**
	 * Get amount of feedback
	 * 
	 * @return int
	 */
	public function feedback()
	{
		return $this->find(
				'//div[contains(@class,"seller-detail-info")]' . 
				'/span[contains(@class,"feedback")]'
			)->item(0)
			 ->getElementsByTagName('a')
			 ->item(0)
			 ->textContent + 0;
	}

	/**
	 * Get feedback precentage
	 * 
	 * @return int
	 */
	public function feedbackPercent()
	{
		$query = $this->find(
					'//div[contains(@class,"seller-detail-info")]' . 
					'/span[contains(@class,"feedback")]'
				)->item(0)
				 ->getElementsByTagName('strong')
				 ->item(0)
				 ->nodeValue;
		
		return str_replace('%', '', $query) + 0;
	}

	/**
	 * Retrieve user join date
	 * 
	 * @return string date
	 */
	public function joinDate()
	{
		$query = $this->find(
					'//div[contains(@class,"seller-detail-info")]' . 
					'/span[not(@class)]'
				)->item(0)
				 ->nodeValue;
		$query = str_replace('join: ', '', strtolower($query));
		$date = new \DateTime($query);

		return $date->format('Y-m-d H:i:s');
	}

	/**
	 * Check whether verified seller
	 * 
	 * @return bool
	 */
	public function verifiedSeller()
	{
		if ($this->find('//div[contains(@class,"vsl-badge")]')->length > 0) {
			return true;
		}

		return false;		
	}

	/**
	 * Find date of thread started
	 * 
	 * @return string 	date thread started
	 */
	public function threadPublished()
	{
		$query = $this->find(
					'//div[contains(@class,"user-details")]/time'
				)->item(0)
				 ->getAttribute('datetime');
		$date = new \DateTime;

		return $date->format('Y-m-d H:i:s');	
	}

	/**
	 * Return product specified entities
	 * 
	 * @return array 	sold, bump, cod
	 */
	public function product()
	{
		$query = $this->find(
				'//div[contains(@class,"item-attributes")]/table/tr'
			);
		foreach ($query as $value) {
			$nodeValue = strtolower($value->nodeValue);
			$titleEntity = trim(explode(':', $nodeValue)[0]);
			$content = explode(':', $nodeValue)[1];

			if($titleEntity === 'terjual') {
				$content = trim($content);
				$this->sold = explode(' ', $content)[0] + 0;
			}
			
			if($titleEntity === 'lokasi') {
				if(strpos($content, 'bisa cod') !== false) {
					$this->cod = true;			
				}
			}
			
			if($titleEntity === 'last sundul') {
				$product['bump'] = trim($content);
				if (strpos(trim($content), 'ago') !== false) {
					$bumpTime = explode(' ', trim($content));
    				$date = new \DateTime;
    				$date->modify('- '.$bumpTime[0] . ' '. $bumpTime[1]);

    				$this->bump = $date->format('Y-m-d H:i:s');
				}
			}

		}

		return [
				'sold' => $this->sold,
				'cod' => $this->cod,
				'bump' => $this->bump,
			   ];
	}

}

