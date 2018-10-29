<?php

namespace Itransition\ShipmentInsurance\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 * @package Helper
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
     * Get module status for shipping method
     *
     * @param string $shippingCode
     * @return bool
     */
    public function isEnable($shippingCode)
    {
        if (!is_string($shippingCode) || empty ($shippingCode)) {
            return false;
        }

        return (bool)$this->getConfig('carriers/'. $shippingCode .'/available');
    }

    /**
     * @param string $shippingCode
     * @return int
     */
    public function getPriceType($shippingCode)
    {
        if (!is_string($shippingCode) || empty ($shippingCode)) {
            return false;
        }

        return (int)$this->getConfig('carriers/'. $shippingCode .'/pricetype');
    }

    /**
     * @param string $shippingCode
     * @return float
     */
    public function getInsuranceValue($shippingCode)
    {
        if (!is_string($shippingCode) || empty ($shippingCode)) {
            return false;
        }

        return (float)$this->getConfig('carriers/'. $shippingCode .'/insurance');
    }

    /**
     * @return string
     */
    public function getFeeLabel()
    {
        return __('Shipment Insurance');
    }
}
