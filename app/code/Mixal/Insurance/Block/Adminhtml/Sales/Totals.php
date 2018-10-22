<?php

namespace Mixal\Insurance\Block\Adminhtml\Sales;

use Magento\Framework\View\Element\Template\Context;
use Mixal\Insurance\Helper\Data as ShipmentHelper;
use Magento\Directory\Model\Currency;
use Magento\Framework\DataObject;

/**
 * Class Totals
 * @package Mixal\Insurance\Block\Adminhtml\Sales
 */
class Totals extends \Magento\Framework\View\Element\Template
{

    /**
     * @var ShipmentHelper
     */
    protected $_helper;
   
    /**
     * @var Currency
     */
    protected $_currency;

    /**
     * @param Context $context
     * @param ShipmentHelper $helper
     * @param Currency $currency
     * @param array $data
     */
    public function __construct(Context $context, ShipmentHelper $helper, Currency $currency, array $data = [])
    {
        parent::__construct($context, $data);
        $this->_helper = $helper;
        $this->_currency = $currency;
    }

    public function getOrder()
    {
        return $this->getParentBlock()->getOrder();
    }

    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    public function getCurrencySymbol()
    {
        return $this->_currency->getCurrencySymbol();
    }

    public function initTotals()
    {
        $this->getParentBlock();
        $this->getOrder();
        $this->getSource();

        if(!$this->getSource()->getFee()) {
            return $this;
        }
        $total = new DataObject(
            [
                'code' => 'fee',
                'value' => $this->getSource()->getFee(),
            ]
        );
        $this->getParentBlock()->addTotalBefore($total, 'grand_total');

        return $this;
    }
}
