<?php

namespace Itransition\ShipmentInsurance\Model\Calculation;

use Magento\Framework\Exception\ConfigurationMismatchException;
use Magento\Quote\Model\Quote;
use Itransition\ShipmentInsurance\Helper\Data as ShipmentHelper;
use Itransition\ShipmentInsurance\Model\Calculation\Calculator\CalculatorInterface;
use Psr\Log\LoggerInterface;
use Magento\Checkout\Model\Session;

/**
 * Class CalculationService acts as wrapper around actual CalculatorInterface so logic valid for all calculations.
 *
 * @package Mixal\Insurance\Model\Calculation
 */
class CalculationService implements CalculatorInterface
{
    /**
     * @var CalculatorFactory
     */
    protected $factory;

    /**
     * @var ShipmentHelper
     */
    protected $helper;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Session
     */
    protected $_checkoutSession;

    /**
     * CalculationService constructor.
     * @param CalculatorFactory $factory
     * @param ShipmentHelper $helper
     * @param LoggerInterface $logger
     * @param Session $checkoutSession
     */
    public function __construct(CalculatorFactory $factory, ShipmentHelper $helper, LoggerInterface $logger, Session $checkoutSession)
    {
        $this->factory = $factory;
        $this->helper = $helper;
        $this->logger = $logger;
        $this->_checkoutSession = $checkoutSession;
    }

    public function calculate(Quote $quote, $shippingMethod = null)
    {
        if ($shippingMethod === null){
            $shippingMethod = $this->_checkoutSession->getQuote()->getShippingAddress()->getShippingMethod();
            $data = explode('_', $shippingMethod);
            $shippingMethod = $data[0];
        }

        // if module not enabled the fee is 0.0
        if (!$this->helper->isEnable($shippingMethod)) {
            return 0.0;
        }

        try {
            return $this->factory->get($shippingMethod)->calculate($quote, $shippingMethod);
        } catch (ConfigurationMismatchException $e) {
            $this->logger->error($e);
            return 0.0;
        }
    }
}
