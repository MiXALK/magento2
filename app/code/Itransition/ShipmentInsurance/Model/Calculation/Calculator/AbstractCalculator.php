<?php

namespace Itransition\ShipmentInsurance\Model\Calculation\Calculator;

use Itransition\ShipmentInsurance\Helper\Data as InsuranceHelper;
use Psr\Log\LoggerInterface;
use Magento\Checkout\Model\Session;


abstract class AbstractCalculator implements CalculatorInterface
{
    /**
     * @var InsuranceHelper
     */
    protected $_helper;

    /**
     * @var Session
     */
    protected $_checkoutSession;

    /**
     * Logger
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * AbstractCalculation constructor.
     *
     * @param InsuranceHelper $helper
     * *@param Session $checkoutSession
     */
    public function __construct(InsuranceHelper $helper, LoggerInterface $logger, Session $checkoutSession)
    {
        $this->_helper = $helper;
        $this->logger = $logger;
        $this->_checkoutSession = $checkoutSession;
    }
}