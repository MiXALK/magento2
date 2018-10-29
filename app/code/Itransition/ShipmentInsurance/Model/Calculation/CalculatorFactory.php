<?php

namespace Itransition\ShipmentInsurance\Model\Calculation;

use Magento\Framework\Exception\ConfigurationMismatchException;
use Magento\Framework\ObjectManagerInterface;
use Itransition\ShipmentInsurance\Helper\Data as ShipmentHelper;
use Itransition\ShipmentInsurance\Model\Config\Source\PriceType;
use Magento\Checkout\Model\Session;

class CalculatorFactory
{
    /**
     * @var ShipmentHelper
     */
    protected $helper;

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var Session
     */
    protected $_checkoutSession;

    /**
     * CalculationFactory constructor.
     *
     * @param ObjectManagerInterface $objectManager
     * @param ShipmentHelper $helper
     * @param Session $checkoutSession
     */
    public function __construct(ObjectManagerInterface $objectManager, ShipmentHelper $helper, Session $checkoutSession)
    {
        $this->helper = $helper;
        $this->objectManager = $objectManager;
        $this->_checkoutSession = $checkoutSession;
    }

    /**
     * @return Calculator\CalculatorInterface
     * @throws ConfigurationMismatchException
     * @param string $shippingMethod
     */
    public function get($shippingMethod = null)
    {
        if ($shippingMethod === null){
            $shippingMethod = $this->_checkoutSession->getQuote()->getShippingAddress()->getShippingMethod();
            $data = explode('_', $shippingMethod);
            $shippingMethod = $data[0];
        }
        switch ($this->helper->getPriceType($shippingMethod)) {
            case PriceType::TYPE_FIXED:
                return $this->objectManager->get(Calculator\FixedCalculator::class);
            case PriceType::TYPE_PERCENTAGE:
                return $this->objectManager->get(Calculator\PercentageCalculator::class);
            default:
                throw new ConfigurationMismatchException(__('Could not find price calculator for type %1', $this->helper->getPriceType($shippingMethod)));
        }
    }
}
