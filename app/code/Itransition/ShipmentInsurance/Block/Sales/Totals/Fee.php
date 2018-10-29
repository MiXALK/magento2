<?php

namespace Itransition\ShipmentInsurance\Block\Sales\Totals;

use Itransition\ShipmentInsurance\Helper\Data as ShipmentHelper;
use Magento\Framework\View\Element\Template\Context;

class Fee extends \Magento\Framework\View\Element\Template
{
    /**
     * @var ShipmentHelper
     */
    protected $_dataHelper;

    /**
     * @var Order
     */
    protected $_order;

    /**
     * @var \Magento\Framework\DataObject
     */
    protected $_source;

    /**
     * @param Context $context
     * @param ShipmentHelper $dataHelper
     * @param array $data
     */
    public function __construct(Context $context, ShipmentHelper $dataHelper, array $data = [])
    {
        $this->_dataHelper = $dataHelper;
        parent::__construct($context, $data);
    }

    /**
     * Check if we need display full tax total info
     *
     * @return bool
     */
    public function displayFullSummary()
    {
        return true;
    }

    /**
     * Get data (totals) source model
     *
     * @return \Magento\Framework\DataObject
     */
    public function getSource()
    {
        return $this->_source;
    }

    public function getStore()
    {
        return $this->_order->getStore();
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * @return array
     */
    public function getLabelProperties()
    {
        return $this->getParentBlock()->getLabelProperties();
    }

    /**
     * @return array
     */
    public function getValueProperties()
    {
        return $this->getParentBlock()->getValueProperties();
    }

    /**
     * @return $this
     */
    public function initTotals()
    {
        $parent = $this->getParentBlock();
        $this->_order = $parent->getOrder();
        $this->_source = $parent->getSource();
        $insurance = new \Magento\Framework\DataObject(
            [
                'code' => 'fee',
                'strong' => false,
                'value' => $this->_source->getFee(),
                'label' => $this->_dataHelper->getFeeLabel(),
            ]
        );
        $parent->addTotal($insurance, 'fee');
        return $this;
    }
}
