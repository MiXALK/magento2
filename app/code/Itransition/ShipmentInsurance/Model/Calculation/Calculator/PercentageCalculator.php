<?php

namespace Itransition\ShipmentInsurance\Model\Calculation\Calculator;

use Magento\Quote\Model\Quote;

class PercentageCalculator extends AbstractCalculator
{
    public function calculate(Quote $quote, $shippingMethod = null)
    {
        if ($shippingMethod === null){
            $shippingMethod = $this->_checkoutSession->getQuote()->getShippingAddress()->getShippingMethod();
            $data = explode('_', $shippingMethod);
            $shippingMethod = $data[0];
        }
        $fee = $this->_helper->getInsuranceValue($shippingMethod);
        $subTotal = $quote->getSubtotal();
        return ($subTotal * $fee) / 100;
    }
}