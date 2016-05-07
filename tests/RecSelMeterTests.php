<?php

namespace Chay22\RecSelMeter\Tests;

use Chay22\RecSelMeter\RecSelMeter;

class RecSelMeterTests extends \PHPUnit_Framework_TestCase
{
	/**
	 * Tests against old created + established store
	 */
	public function testScore()
	{
		$recselmeter = new RecSelMeter('http://fjb.kaskus.co.id/product/000000000000000002632368');
		$score = $recselmeter->calculate();
		$this->assertGreaterThan(10, $score);
	}
}
