<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */
namespace Praxigento\Logging;

use Magento\Setup\Exception;
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
            ->with($this->equalTo('Magento\Framework\Logger\Monolog'))
            ->willReturn($mockDefaultLogger);
        /**
         * Perform testing.
         */
        $logger = new Logger();
        $this->assertTrue($logger instanceof \Praxigento\Logging\Logger);
    }

    public function test_constructor_cascadedAbsolutePath() {
        $CONFIG_FILE_NAME = __DIR__ . '/logging.yaml';
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
        /**
         * Perform testing.
         */
        $logger = new Logger($CONFIG_FILE_NAME, $LOGGER_NAME);
        $this->assertTrue($logger instanceof \Praxigento\Logging\Logger);
    }

    public function test_constructor_relativePath_missedConfig() {
        if(!defined('BP')) {
            define('BP', 'some/path/that/is/not/exist');
        }
        $CONFIG_FILE_NAME = './logging.yaml.missed';
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
            ->willReturn(false);
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
        $logger = new Logger($CONFIG_FILE_NAME, $LOGGER_NAME);
        $this->assertTrue($logger instanceof \Praxigento\Logging\Logger);
    }

    public function test_constructor_exception() {
        $CONFIG_FILE_NAME = __DIR__ . '/logging.yaml';
        $LOGGER_NAME = 'defaultLoggerName';
        $REASON = 'any reason';
        /** Mock ObjectManager and runtime environment */
        $mockObm = $this
            ->getMockBuilder('Magento\TestFramework\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();
        ObjectManager::setInstance($mockObm);
        $mockObm
            ->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('Symfony\Component\Filesystem\Filesystem'))
            ->willThrowException(new Exception($REASON));
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
        $mockDefaultLogger
            ->expects($this->once())
            ->method('warning')
            ->with($this->equalTo($REASON));
        /**
         * Perform testing.
         */
        $logger = new Logger($CONFIG_FILE_NAME, $LOGGER_NAME);
        $this->assertTrue($logger instanceof \Praxigento\Logging\Logger);
    }

    public function test_logMethods() {
        $MSG = 'message';
        $CONTEXT = [ ];
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
            ->with($this->equalTo('Magento\Framework\Logger\Monolog'))
            ->willReturn($mockDefaultLogger);
        // logger methods
        $mockDefaultLogger
            ->expects($this->once())
            ->method('alert');
        $mockDefaultLogger
            ->expects($this->once())
            ->method('critical');
        $mockDefaultLogger
            ->expects($this->once())
            ->method('debug');
        $mockDefaultLogger
            ->expects($this->once())
            ->method('emergency');
        $mockDefaultLogger
            ->expects($this->once())
            ->method('error');
        $mockDefaultLogger
            ->expects($this->once())
            ->method('info');
        $mockDefaultLogger
            ->expects($this->once())
            ->method('log');
        $mockDefaultLogger
            ->expects($this->once())
            ->method('notice');
        $mockDefaultLogger
            ->expects($this->once())
            ->method('warning');
        /**
         * Perform testing.
         */
        $logger = new Logger();
        $this->assertTrue($logger instanceof \Praxigento\Logging\Logger);
        $logger->alert($MSG, $CONTEXT);
        $logger->critical($MSG, $CONTEXT);
        $logger->debug($MSG, $CONTEXT);
        $logger->emergency($MSG, $CONTEXT);
        $logger->error($MSG, $CONTEXT);
        $logger->info($MSG, $CONTEXT);
        $logger->log($MSG, $CONTEXT);
        $logger->notice($MSG, $CONTEXT);
        $logger->warning($MSG, $CONTEXT);
    }
}