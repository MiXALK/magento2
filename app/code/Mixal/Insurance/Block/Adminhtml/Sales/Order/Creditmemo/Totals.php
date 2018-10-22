<?php

namespace Mixal\Insurance\Block\Adminhtml\Sales\Order\Creditmemo;

use Magento\Framework\View\Element\Template\Context;
use Mixal\Insurance\Helper\Data as ShipmentHelper;
use Magento\Framework\DataObject;


class Totals extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Sales\Model\Order\Creditmemo
     */
    protected $_creditmemo = null;

    /**
     * @var \Magento\Framework\DataObject
     */
    protected $_source;

    /**
     * @var ShipmentHelper
     */
    protected $_helper;

    /**
     * @param Context $context
     * @param ShipmentHelper $helper
     * @param array $data
     */
    public function __construct(Context $context, ShipmentHelper $helper, array $data = [])
    {
        $this->_helper = $helper;
        parent::__construct($context, $data);
    }

    /**
     * @return \Magento\Framework\DataObject
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    public function getCreditmemo()
    {
        return $this->getParentBlock()->getCreditmemo();
    }

    public function initTotals()
    {
        $this->getParentBlock();
        $this->getCreditmemo();
        $this->getSource();

        if (!$this->getSource()->getFee()) {
            return $this;
        }
        
        $fee = new DataObject(
            [
                'code' => 'fee',
                'strong' => false,
                'value' => $this->getSource()->getFee(),
            ]
        );

        $this->getParentBlock()->addTotalBefore($fee, 'grand_total');

        return $this;
    }
}
