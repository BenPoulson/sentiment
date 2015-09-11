<?php
	
	use BenPoulson\Sentiment\Sentiment;

	class SentimentTest extends PHPUnit_Framework_TestCase {

		public function testCanInitialise() {
			$sa = new Sentiment();
			echo $sa->rebuildIndex();die;
			$this->assertTrue(true);
		}

	}