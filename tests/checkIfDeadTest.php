<?php

require_once dirname(__FILE__) . '/../IABot/checkIfDead.php';

class checkIfDeadTest extends PHPUnit_Framework_TestCase {

	public function testDeadlinksTrue() {
		$obj = new checkIfDead();
		$urls = array(
					'https://en.wikipedia.org/nothing',
					'http://www.copart.co.uk/c2/specialSearch.html?_eventId=getLot&execution=e1s2&lotId=10543580',
					'http://forums.lavag.org/Industrial-EtherNet-EtherNet-IP-t9041.html'
				);
		$result = $obj->checkDeadlinks( $urls );
		$expected = array( true, true, true );
		$this->assertEquals( $result['results'], $expected );
	}


	public function testDeadlinksFalse() {
		$obj = new checkIfDead();
		$urls = array(
					'https://en.wikipedia.org/wiki/Main_Page',
					'https://en.wikipedia.org/w/index.php?title=Republic_of_India',
					'https://astraldynamics.com',
					'http://www.eonline.com/au/news/386489/2013-grammy-awards-winners-the-complete-list',
					'http://flysunairexpress.com/#about'
				);
		$result = $obj->checkDeadlinks( $urls );
		$expected = array( false, false, false, false, false );
		$this->assertEquals( $result['results'], $expected );
	}


	public function testCleanUrl() {
		$obj = new checkIfDead();

		// workaround to make private function testable
		$reflection = new \ReflectionClass( get_class( $obj ) );
		$method = $reflection->getMethod('cleanUrl');
		$method->setAccessible( true );

		$this->assertEquals( $method->invokeArgs( $obj, array( 'http://google.com?q=blah' ) ), 'google.com?q=blah' );
		$this->assertEquals( $method->invokeArgs( $obj, array( 'https://www.google.com/' ) ), 'google.com' );
		$this->assertEquals( $method->invokeArgs( $obj, array( 'ftp://google.com/#param=1' ) ), 'google.com' );
		$this->assertEquals( $method->invokeArgs( $obj, array( '//google.com' ) ), 'google.com' );
		$this->assertEquals( $method->invokeArgs( $obj, array( 'www.google.www.com' ) ), 'google.www.com' );
	}

}

?>
