<?php

namespace Chay22\RecSelMeter\Tests;

use Chay22\RecSelMeter\Config;

class ConfigTests extends \PHPUnit_Framework_TestCase
{
	public $config;
	/**
	 * Tests against old created + established store
	 */
	function __construct()
	{
		$this->config = new Config;
	}

	public function testNew()
	{
		$config = new Config;
		$config->newRank(['kaskus test' => 100]);
		$this->assertArrayHasKey('kaskus test', $config->data()['rank']);
		$this->assertCount(1, $config->data()['rank']);
	}

	public function testAdd()
	{
		$config = new Config;
		$config->addRank(['kaskus test' => 100]);
		$this->assertArrayHasKey('kaskus test', $config->data()['rank']);
		$this->assertCount(15, $config->data()['rank']);
	}

	public function testSet()
	{
		$config = new Config;
		$config->setStoreActive([7 => 3]);
		$this->assertEquals(3, $config->data()['storeActive'][7]);
	}

}