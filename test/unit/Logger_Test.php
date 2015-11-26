<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */
namespace Praxigento\Logging;

use Magento\TestFramework\ObjectManager;

include_once(__DIR__ . '/phpunit_bootstrap.php');

class Logger_UnitTest extends \PHPUnit_Framework_TestCase {

    public function test_constructor_defaultMagentoLogger() {
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

    public function test_constructor_cascaded() {
        $CONFIG_FILE_NAME = 'path/to/file/yaml';
        $LOGGER_NAME = 'defaultLoggerName';
        /** Mock ObjectManager and runtime environment */
        $mockObm = $this
            ->getMockBuilder('Magento\TestFramework\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();
        ObjectManager::setInstance($mockObm);
        // $this->getFilesystem()
        $mockFs = $this
            ->getMockBuilder('Symfony\Component\Filesystem\Filesystem')
            ->getMock();
        $mockObm
            ->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('Symfony\Component\Filesystem\Filesystem'))
            ->willReturn($mockFs);
        $mockFs
            ->expects($this->once())
            ->method('isAbsolutePath')
            ->willReturn(true);
        // $this->_logger = ObjectManager::getInstance()->get('Magento\Framework\Logger\Monolog');
        $mockDefaultLogger = $this
            ->getMockBuilder('Magento\Framework\Logger\Monolog')
            ->disableOriginalConstructor()
            ->getMock();
        $mockObm
            ->expects($this->at(1))
            ->method('get')
            ->with($this->equalTo('Magento\Framework\Logger\Monolog'))
            ->willReturn($mockDefaultLogger);
        /**
         * Perform testing.
         */
        $cfg = new Logger($CONFIG_FILE_NAME, $LOGGER_NAME);
        $this->assertTrue($cfg instanceof \Praxigento\Logging\Logger);
    }

}