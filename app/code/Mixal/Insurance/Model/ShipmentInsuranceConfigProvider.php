<?php

namespace Mixal\Insurance\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Checkout\Model\Session;
use Mixal\Insurance\Helper\Data as ShipmentHelper;
use Mixal\Insurance\Model\Calculation\Calculator\CalculatorInterface;

/**
 * Class ShipmentInsuranceConfigProvider
 * @package Mixal\Insurance\Model
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
     */
    public function __construct(ShipmentHelper $helper, Session $checkoutSession, CalculatorInterface $calculator) {
        $this->helper = $helper;
        $this->checkoutSession = $checkoutSession;
        $this->calculator = $calculator;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $insuranceConfig = [];
        $enabled = $this->helper->isEnable();
        $insuranceConfig['fee_label'] = $this->helper->getFeeLabel();
        $quote = $this->checkoutSession->getQuote();
        $insuranceAmount = $this->calculator->calculate($quote);
        $insuranceConfig['insurance_amount'] = $insuranceAmount;
        $insuranceConfig['show_hide_insurance'] = ($enabled && $quote->getFee()) ? true : false;
        $insuranceConfig['show_hide_insurance_shipblock'] = $enabled ? true : false;
        ;
        return $insuranceConfig;
    }
}