<?php

namespace Chay22\RecSelMeter\Tests;

use Chay22\RecSelMeter\Parser;

class ParserTests extends \PHPUnit_Framework_TestCase
{
	public $url;
	public $parser;
	/**
	 * Tests against old created + established store
	 */
	function __construct()
	{
		$this->url = 'http://fjb.kaskus.co.id/product/000000000000000002632368';
		$this->parser = new Parser($this->url);
	}

	public function testURL()
	{
		$this->assertEquals('http://fjb.kaskus.co.id/product/000000000000000002632368', $this->url);
	}

	public function testConnectionStatus()
	{
		$this->assertEquals(200, $this->parser->header['http_code']);
	}

	public function testUsername()
	{
		$this->assertEquals('squadkuna', $this->parser->username());
	}

	public function testUserID()
	{
		$this->assertEquals(691087, $this->parser->userID());
	}

	/**
	 * Old created store doesn't has image
	 */
	public function testImageCount()
	{
		$this->assertEmpty($this->parser->countImage());
	}

	public function testStorePublished()
	{
		$this->assertEquals('2009-10-24 10:26:08', $this->parser->threadPublished());
	}

	public function testVerifiedSeller()
	{
		$this->assertFalse($this->parser->verifiedSeller());
	}

}