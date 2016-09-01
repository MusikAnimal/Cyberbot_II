<?php

require_once dirname(__FILE__) . '/config.php';
require_once dirname(__FILE__) . '/botclasses.php';

class acceptanceTests extends PHPUnit_Framework_TestCase {
  // Text files representing the test cases. Filenames should end in .txt
  //   and be located in the ./test_cases directory
  const TEST_CASE_SOURCE = 'acceptance-source';
  const TEST_CASE_RESULT = 'acceptance-result';

  /**
   * Initialize botclasses and login Community_Tech_bot
   */
  public function setUp() {
    $this->api = new wikipedia( 'https://test.wikipedia.org/w/api.php' );
    $this->api->login( ACCEPTANCE_USERNAME, ACCEPTANCE_PASSWORD );
    $this->createTestCases();
  }

  /**
   * Run the bot on the configured page in deadlink.config.local.inc.php
   * The configured page should be the same as $this->getTestPageName( self::TEST_CASE_SOURCE )
   */
  public function testPages() {
    // Require deadlink.php to start the bot
    require dirname(__FILE__) . '/../../deadlink.php';

    // get the page the bot edited
    $testPageName = $this->getTestPageName( self::TEST_CASE_SOURCE );
    $testPageContents = $this->api->getPage( $testPageName );
    $testCaseContents = $this->getTestCaseContents( self::TEST_CASE_RESULT );

    // compare what is expected with the new version of the page
    $this->assertEquals( $testCaseContents, $testPageContents );
  }

  /**
   * Creates the test case page on the configured wiki, located within Community_Tech_bot's userspace
   */
  private function createTestCases() {
    $page = $this->getTestPageName( self::TEST_CASE_SOURCE );
    $text = $this->getTestCaseContents( self::TEST_CASE_SOURCE );
    $this->api->edit( $page, $text );
  }

  /**
   * Get the wiki path for the given test page
   * @param  string $page Page name
   * @return string Given page name within Community_Tech_bot's ( or ACCEPTANCE_USERNAME ) userspace
   */
  private function getTestPageName( $page ) {
    return 'User:' . ACCEPTANCE_USERNAME . "/$page";
  }

  /**
   * Get the contents of the given test case from the file system
   * $file should be located in ./test_cases and have a .txt extension
   * @param  string $file File name without extension
   * @return string Contents of test case
   */
  private function getTestCaseContents( $file ) {
    return file_get_contents( "./test_cases/$file.txt" );
  }
}
