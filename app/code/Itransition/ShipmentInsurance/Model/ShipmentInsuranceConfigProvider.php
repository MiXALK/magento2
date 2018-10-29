<?php

namespace Itransition\ShipmentInsurance\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Checkout\Model\Session;
use Itransition\ShipmentInsurance\Helper\Data as ShipmentHelper;
use Itransition\ShipmentInsurance\Model\Calculation\Calculator\CalculatorInterface;

/**
 * Class ShipmentInsuranceConfigProvider
 * @package Itransition\ShipmentInsurance\Model
 */
class ShipmentInsuranceConfigProvider implements ConfigProviderInterface
{
    /**
     * @var ShipmentHelper
     */
    protected $helper;

    /**
     * @var Session
     */
    protected $checkoutSession;

    /**
     * @var CalculatorInterface
     */
    protected $calculator;

    /**
     * @param ShipmentHelper $helper
     * @param Session $checkoutSession
     * @param CalculatorInterface $calculator
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(ShipmentHelper $helper,
                                Session $checkoutSession,
                                CalculatorInterface $calculator
    ) {
        $this->helper = $helper;
        $this->checkoutSession = $checkoutSession;
        $this->calculator = $calculator;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $shippingMethod = $this->checkoutSession->getQuote()->getShippingAddress()->getShippingMethod();
        $data = explode('_', $shippingMethod);
        $shippingMethod = $data[0];

        $insuranceConfig = [];
        $enabled = $this->helper->isEnable($shippingMethod);
        $insuranceConfig['fee_label'] = $this->helper->getFeeLabel();
        $quote = $this->checkoutSession->getQuote();
        $insuranceAmount = $this->calculator->calculate($quote);
        $insuranceConfig['insurance_amount'] = $insuranceAmount;
        $insuranceConfig['show_hide_insurance'] = ($enabled && $quote->getFee()) ? true : false;
        return $insuranceConfig;
    }
}
