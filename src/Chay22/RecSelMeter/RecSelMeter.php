<?php

namespace Chay22\RecSelMeter;

use Chay22\RecSelMeter\Parser;
use Chay22\RecSelMeter\Config;

class RecSelMeter
{
	/**
	 * Data of current store
	 *
	 * @var array $store
	 */
	protected $store;

	/**
	 * Set of configuration
	 * 
	 * @var object $config
	 */
	protected $config;

	/**
	 * Score of current store
	 *
	 * @var int $score
	 */
	protected $score = 0;

	/**
	 * @param   string  $url  URL of the store
	 */
	function __construct($url)
	{
		$store = new Parser($url);
		$this->store = $store->fetch();

		$this->config = new Config;
	}

	/**
	 * Return object of Config
	 * 
	 * @return object
	 */
	public function config()
	{
		return $this->config;
	}

	/**
	 * Return score of current store
	 * 
	 * @return int
	 */
	public function calculate()
	{
		$this->sold();
		$this->rank();
		$this->feedback();
		$this->storeActive();
		$this->cod();
		$this->accountAge();
		$this->imageCount();

		return $this->score;
	}

	/**
	 * Calculate amount of item of store sold
	 * 
	 * @return void
	 */
	protected function sold()
	{
		foreach ($this->config->data()['sold'] as $key => $value) {
			if ($this->store['sold'] >= $key) {
				$this->score += $value;
			}
		}
	}

	/**
	 * Add the score point based of seller rank
	 * 
	 * @return void
	 */
	protected function rank()
	{
		foreach ($this->config->data()['rank'] as $key => $value) {
			if (stripos($this->store['rank'], $key) !== false) {
				$this->score += $value;
			}
		}
	}

	/**
	 * Add the score point based of amount of seller feedback
	 * and its feedback percent
	 * 
	 * @return void
	 */
	protected function feedback()
	{
		if ($this->store['feedback_percent'] == 0) {
			return;
		}
		$feedback = $this->store['feedback'] * $this->config->data()['feedback'];
		$feedbackPercent = ($this->store['feedback_percent'] / $this->config->data()['feedbackPercent']);
		$score = round($feedback / $feedbackPercent);
		$this->score += $score;
	}

	/**
	 * Add score point based of time from store published time
	 * and latest bump (sundul) attempt
	 * 
	 * @return void
	 */
	protected function storeActive()
	{
		$storePublished = new \DateTime($this->store['published_at']);
		$storeBumped = new \DateTime($this->store['last_bump']);
		$interval = $storePublished->diff($storeBumped);
		$interval = $interval->days;
		
		foreach ($this->config->data()['storeActive'] as $key => $value) {
			if ($interval <= $key) {
				$this->score += $value;
			}
		}
	}

	/**
	 * Add a score based of seller provides COD or no
	 * 
	 * @return void
	 */
	protected function cod()
	{
		if ($this->store['cod'] !== false) {
			$this->score += $this->config->data()['cod'];
		}	
	}

	/**
	 * Add a score based of seller account age
	 * 
	 * @return void
	 */
	protected function accountAge()
	{
		$joinDate = new \DateTime($this->store['join_date']);
		$currentDate = new \DateTime();
		$interval = $joinDate->diff($currentDate);
		$interval = $interval->y;
		$this->score += ($interval * $this->config->data()['accountAge']);
	}

	/**
	 * Add a score based of amount of image seller provides
	 * 
	 * @return void
	 */
	protected function imageCount()
	{
		$this->score += $this->store['image_count'] * $this->config->data()['imageCount'];
	}
}
