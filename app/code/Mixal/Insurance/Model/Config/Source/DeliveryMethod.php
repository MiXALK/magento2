<?php

namespace Mixal\Insurance\Model\Config\Source;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Shipping\Model\Config;
use Magento\Framework\Option\ArrayInterface;
use \Magento\Framework\DataObject;

class DeliveryMethod extends DataObject implements ArrayInterface
{
    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;
    /**
     * @var Config
     */
    protected $_deliveryModelConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param Config               $deliveryModelConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Config $deliveryModelConfig
    ) {

        $this->_scopeConfig = $scopeConfig;
        $this->_deliveryModelConfig = $deliveryModelConfig;
    }

    public function toOptionArray()
    {
        $deliveryMethods = $this->_deliveryModelConfig->getActiveCarriers();
        $deliveryMethodsArray = array();
        foreach ($deliveryMethods as $shippigCode => $shippingModel) {
            $shippingTitle = $this->_scopeConfig->getValue('carriers/'.$shippigCode.'/title');
            $deliveryMethodsArray[$shippigCode] = array(
                'label' => $shippingTitle,
                'value' => $shippigCode
            );
        }
        return $deliveryMethodsArray;
    }
}
