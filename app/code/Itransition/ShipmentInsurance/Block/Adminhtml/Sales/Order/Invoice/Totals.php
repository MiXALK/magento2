<?php

namespace Itransition\ShipmentInsurance\Block\Adminhtml\Sales\Order\Invoice;

use Magento\Framework\View\Element\Template\Context;
use Itransition\ShipmentInsurance\Helper\Data as ShipmentHelper;
use Magento\Framework\DataObject;

class Totals extends \Magento\Framework\View\Element\Template
{

    /**
     * @var ShipmentHelper
     */
    protected $_helper;

    /**
     * @var \Magento\Sales\Model\Order\Invoice
     */
    protected $_invoice = null;

    /**
     * @var \Magento\Framework\DataObject
     */
    protected $_source;

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

    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    public function getInvoice()
    {
        return $this->getParentBlock()->getInvoice();
    }

    public function initTotals()
    {
        $this->getParentBlock();
        $this->getInvoice();
        $this->getSource();

        if (!$this->getSource()->getFee()) {
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
