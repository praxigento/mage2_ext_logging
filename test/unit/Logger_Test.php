<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */
namespace Praxigento\Logging;

use Magento\TestFramework\ObjectManager;

include_once(__DIR__ . '/phpunit_bootstrap.php');

class Logger_UnitTest extends \PHPUnit_Framework_TestCase {

    public function test_constructor_empty() {
        /** Mock ObjectManager and runtime environment */
        $mockObm = $this
            ->getMockBuilder('Magento\TestFramework\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();
        ObjectManager::setInstance($mockObm);
        // $this->_logger = ObjectManager::getInstance()->get('Magento\Framework\Logger\Monolog');
        $mockDefaultLogger = $this
            ->getMockBuilder('Magento\Framework\Logger\Monolog')
            ->disableOriginalConstructor()
            ->getMock();
        $mockObm
            ->expects($this->once())
            ->method('get')
            ->willReturn($mockDefaultLogger);
        /**
         * Perform testing.
         */
        $cfg = new Logger();
        $this->assertTrue($cfg instanceof \Praxigento\Logging\Logger);
    }

}