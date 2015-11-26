<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */
namespace Praxigento\Logger;
include_once(__DIR__ . '/phpunit_bootstrap.php');

class Logger_UnitTest extends \PHPUnit_Framework_TestCase {

    public function test_constructor_empty() {
        $cfg = new Logger();
        $this->assertTrue($cfg instanceof \Praxigento\Logging\Logger);
    }

}