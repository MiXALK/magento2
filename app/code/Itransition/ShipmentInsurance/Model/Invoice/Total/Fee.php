<?php

namespace Itransition\ShipmentInsurance\Model\Invoice\Total;

use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;

/**
 * Class Fee
 * @package Itransition\ShipmentInsurance\Model\Invoice\Total
 */
class Fee extends AbstractTotal
{
    /**
     * @param Invoice $invoice
     * @return $this
     */
    public function collect(Invoice $invoice)
    {
        $invoice->setFee(0);
        $invoice->setBaseFee(0);
        $amount = $invoice->getOrder()->getFee();
        $invoice->setFee($amount);
        $amount = $invoice->getOrder()->getBaseFee();
        $invoice->setBaseFee($amount);
        $invoice->setGrandTotal($invoice->getGrandTotal() + $invoice->getFee());
        $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $invoice->getFee());

        return $this;
    }
}
