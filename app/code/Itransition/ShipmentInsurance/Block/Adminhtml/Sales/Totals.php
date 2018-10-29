<?php

namespace Itransition\ShipmentInsurance\Block\Adminhtml\Sales;

use Magento\Framework\View\Element\Template\Context;
use Itransition\ShipmentInsurance\Helper\Data as ShipmentHelper;
use Magento\Directory\Model\Currency;
use Magento\Framework\DataObject;

/**
 * Class Totals
 * @package Itransition\ShipmentInsurance\Block\Index\Sales
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

    /**
     * @return $this
     */
    public function initTotals()
    {
        $this->getParentBlock();
        $this->getOrder();
        $this->getSource();

        if (!$this->getSource()->getFee()) {
            return $this;
        }
        $total = new DataObject(
            [
                'code' => 'fee',
                'value' => $this->getSource()->getFee(),
                'label' => $this->_helper->getFeeLabel(),
            ]
        );
        $this->getParentBlock()->addTotalBefore($total, 'grand_total');
        return $this;
    }
}
