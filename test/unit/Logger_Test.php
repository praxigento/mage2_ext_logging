<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */
namespace Praxigento\Logging;

use Magento\Framework\ObjectManagerInterface;
use Magento\Setup\Exception;
use Mockery;

include_once(__DIR__ . '/phpunit_bootstrap.php');

class Logger_UnitTest extends \PHPUnit_Framework_TestCase
{
    public function test_constructor_cascadedAbsolutePath()
    {
        /* === Test Data === */
        $CONFIG_FILE_NAME = __DIR__ . '/logging.yaml';
        $LOGGER_NAME = 'defaultLoggerName';
        /* === Setup Mocks === */
        $mObm = Mockery::mock(ObjectManagerInterface::class);
        // $this->_initLoggerCascade($configFile, $loggerName);
        // $fs = $this->_obm->get(Filesystem::class);
        $mFs = Mockery::mock(\Symfony\Component\Filesystem\Filesystem::class);
        $mObm->shouldReceive('get')->once()
            ->andReturn($mFs);
        // if ($fs->isAbsolutePath($configFile)) {
        $mFs->shouldReceive('isAbsolutePath')->once()
            ->andReturn(true);
        /* === Call and asserts  === */
        $logger = new Logger($mObm, $CONFIG_FILE_NAME, $LOGGER_NAME);
        $this->assertTrue($logger instanceof \Praxigento\Logging\Logger);
    }

    public function test_constructor_defaultMagentoLogger()
    {
        /* === Setup Mocks === */
        $mObm = Mockery::mock(ObjectManagerInterface::class);
        // $this->_logger = $this->_obm->get(\Magento\Framework\Logger\Monolog::class);
        $mLogger = Mockery::mock(\Magento\Framework\Logger\Monolog::class);
        $mObm->shouldReceive('get')->once()
            ->andReturn($mLogger);
        /* === Call and asserts  === */
        $logger = new Logger($mObm);
        $this->assertTrue($logger instanceof \Praxigento\Logging\Logger);
    }

    public function test_constructor_exception()
    {
        /* === Test Data === */
        $CONFIG_FILE_NAME = __DIR__ . '/logging.yaml';
        $LOGGER_NAME = 'defaultLoggerName';
        $REASON = 'any reason';
        /* === Setup Mocks === */
        $mObm = Mockery::mock(ObjectManagerInterface::class);
        // private function _initLoggerCascade($configFile, $loggerName)
        // $fs = $this->_obm->get(Filesystem::class);
        $mObm->shouldReceive('get')->once()
            ->andThrow(new \Exception($REASON));
        // } finally {
        // $this->_logger = $this->_obm->get(\Magento\Framework\Logger\Monolog::class);
        $mLogger = Mockery::mock(\Magento\Framework\Logger\Monolog::class);
        $mObm->shouldReceive('get')->once()
            ->andReturn($mLogger);
        // $this->warning($err);
        $mLogger->shouldReceive('warning')->once()
            ->with($REASON, []);
        /* === Call and asserts  === */
        $logger = new Logger($mObm, $CONFIG_FILE_NAME, $LOGGER_NAME);
        $this->assertTrue($logger instanceof \Praxigento\Logging\Logger);
    }

    public function test_constructor_relativePath_missedConfig()
    {
        /* === Test Data === */
        if (!defined('BP')) {
            define('BP', 'some/path/that/is/not/exist');
        }
        $CONFIG_FILE_NAME = './logging.yaml.missed';
        $LOGGER_NAME = 'defaultLoggerName';
        /* === Setup Mocks === */
        $mObm = Mockery::mock(ObjectManagerInterface::class);
        // $this->_initLoggerCascade($configFile, $loggerName);
        // $fs = $this->_obm->get(Filesystem::class);
        $mFs = Mockery::mock(\Symfony\Component\Filesystem\Filesystem::class);
        $mObm->shouldReceive('get')->once()
            ->andReturn($mFs);
        // if ($fs->isAbsolutePath($configFile)) {
        $mFs->shouldReceive('isAbsolutePath')->once()
            ->andReturn(false);
        // } finally {
        // $this->_logger = $this->_obm->get(\Magento\Framework\Logger\Monolog::class);
        $mLogger = Mockery::mock(\Magento\Framework\Logger\Monolog::class);
        $mObm->shouldReceive('get')->once()
            ->andReturn($mLogger);
        // $this->warning($err);
        $mLogger->shouldReceive('warning')->once();
        /* === Call and asserts  === */
        $logger = new Logger($mObm, $CONFIG_FILE_NAME, $LOGGER_NAME);
        $this->assertTrue($logger instanceof \Praxigento\Logging\Logger);
    }

    public function test_logMethods()
    {
        /* === Test Data === */
        $MSG = 'message';
        $CONTEXT = [];
        /* === Setup Mocks === */
        $mObm = Mockery::mock(ObjectManagerInterface::class);
        // $this->_logger = $this->_obm->get(\Magento\Framework\Logger\Monolog::class);
        $mLogger = Mockery::mock(\Magento\Framework\Logger\Monolog::class);
        $mObm->shouldReceive('get')->once()
            ->andReturn($mLogger);
        // logger methods
        $mLogger->shouldReceive('alert', 'critical', 'debug', 'emergency', 'error', 'info', 'log', 'notice', 'warning');
        /**
         * Perform testing.
         */
        $logger = new Logger($mObm);
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