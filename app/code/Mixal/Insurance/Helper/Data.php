<?php

namespace Mixal\Insurance\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 * @package Mixal\Insurance\Helper
 */
class Data extends AbstractHelper
{
    /**
     * @param $config
     * @return mixed
     */
    public function getConfig($config)
    {
        return $this->scopeConfig->getValue($config, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get module status
     *
     * @return bool
     */
    public function isEnable()
    {
        return (bool) $this->getConfig('insurance/general/active');
    }

    /**
     * Get insurance price type
     *
     * @return int
     */
    public function getPriceType()
    {
        return (int) $this->getConfig('insurance/general/pricetype');
    }

    /**
     * Get insurance value
     *
     * @return float
     */
    public function getInsuranceValue()
    {
        return (float) $this->getConfig('insurance/general/price');
    }

    /**
     * Get custom fee
     *
     * @return string
     */
    public function getFeeLabel()
    {
        return __('Shipment Insurance');
    }
}
