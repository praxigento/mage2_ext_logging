<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */
namespace Flancer32\Sample\Test;
/**
 * Application to launch functional tests.
 */
class FuncTestApp implements \Magento\Framework\AppInterface {
    /**
     * @var \Magento\Framework\App\Console\Response
     */
    protected $_response;
    /** @var \Magento\Store\Model\StoreManagerInterface */
    protected $_storeManager;

    /**
     * App constructor.
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Console\Response $response
    ) {
        $this->_storeManager = $storeManager;
        $this->_response = $response;
    }

    /**
     * Ability to handle exceptions that may have occurred during bootstrap and launch
     *
     * Return values:
     * - true: exception has been handled, no additional action is needed
     * - false: exception has not been handled - pass the control to Bootstrap
     *
     * @param \Magento\Framework\App\Bootstrap $bootstrap
     * @param \Exception                       $exception
     *
     * @return bool
     */
    public function catchException(
        \Magento\Framework\App\Bootstrap $bootstrap,
        \Exception $exception
    ) {
        return false;
    }

    /**
     * Launch application. Prevent application termination on sent response, initialize DB connection.
     *
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function launch() {
        $this->_response->terminateOnSend(false);
        $this->_storeManager->getStores(false, true);
        return $this->_response;
    }
}