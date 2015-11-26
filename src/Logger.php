<?php
/**
 * Wrapper for default Magento 2 logger or for Cascaded Monolog logger.
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Praxigento\Logging;

use Cascade\Cascade;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Webapi\Exception;

class Logger implements \Psr\Log\LoggerInterface {
    /**
     * Logger (default M2 or Cascaded Monolog).
     *
     * @var \Psr\Log\LoggerInterface
     */
    private $_logger;
    const DEFAULT_LOGGER_NAME = 'main';

    /**
     * Logger constructor.
     *
     * @param null $configFile
     */
    public function __construct(
        $configFile = null,
        $loggerName = self::DEFAULT_LOGGER_NAME
    ) {
        if(is_null($configFile)) {
            /* use default Magento 2 logger */
            $this->_initLoggerMagento();
        } else {
            /* use Cascaded Monolog */
            $this->_initLoggerCascade($configFile, $loggerName);
        }
    }

    /**
     * Use Magento 2 default logger.
     */
    private function  _initLoggerMagento() {
        $this->_logger = ObjectManager::getInstance()->get('Magento\Framework\Logger\Monolog');
    }

    /**
     * This is a dirty trick to get Filesystem component that can be mocked in tests.
     *
     * @return \Symfony\Component\Filesystem\Filesystem
     */
    public function getFilesystem() {
        $result = new \Symfony\Component\Filesystem\Filesystem();
        return $result;
    }

    /**
     * Configure Cascaded Monolog logger and use it.
     *
     * @param        $configFile
     * @param string $loggerName
     */
    private function  _initLoggerCascade($configFile, $loggerName) {
        $err = '';
        try {
            $fs = $this->getFilesystem();
            if($fs->isAbsolutePath($configFile)) {
                $fileName = $configFile;
            } else {
                $fileName = BP . '/' . $configFile;
            }
            $realPath = realpath($fileName);
            if($realPath) {
                Cascade::fileConfig($realPath);
                $this->_logger = Cascade::getLogger($loggerName);
            } else {
                $err = "Cannot open logging configuration file '$fileName'. Default Magento logger is used.";
            }
        } catch(Exception $e) {
            $err = $e->getMessage();
        } finally {
            if(is_null($this->_logger)) {
                $this->_initLoggerMagento();
                $this->warning($err);
            }
        }
    }

    public function emergency($message, array $context = [ ]) {
        $this->_logger->emergency($message, $context);
    }

    public function alert($message, array $context = [ ]) {
        $this->_logger->alert($message, $context);
    }

    public function critical($message, array $context = [ ]) {
        $this->_logger->critical($message, $context);
    }

    public function error($message, array $context = [ ]) {
        $this->_logger->error($message, $context);
    }

    public function warning($message, array $context = [ ]) {
        $this->_logger->warning($message, $context);
    }

    public function notice($message, array $context = [ ]) {
        $this->_logger->notice($message, $context);
    }

    public function info($message, array $context = [ ]) {
        $this->_logger->info($message, $context);
    }

    public function debug($message, array $context = [ ]) {
        $this->_logger->debug($message, $context);
    }

    public function log($level, $message, array $context = [ ]) {
        $this->_logger->log($level, $message, $context);
    }
}