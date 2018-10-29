<?php

namespace Itransition\ShipmentInsurance\Model\Creditmemo\Total;

use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal;

/**
 * Class Fee
 * @package Itransition\ShipmentInsurance\Model\Creditmemo\Total
 */
class Fee extends AbstractTotal
{
    /**
     * @param Creditmemo $creditmemo
     * @return $this
     */
    public function collect(Creditmemo $creditmemo)
    {
        $creditmemo->setFee(0);
        $creditmemo->setBaseFee(0);

        $amount = $creditmemo->getOrder()->getFee();
        $creditmemo->setFee($amount);
        $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $amount);

        $baseAmount = $creditmemo->getOrder()->getBaseFee();
        $creditmemo->setBaseFee($baseAmount);
        $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $baseAmount);

        return $this;
    }
}
