<?php

namespace Mixal\Insurance\Model\Calculation\Calculator;

use Magento\Quote\Model\Quote;

class PercentageCalculator extends AbstractCalculator
{
    public function calculate(Quote $quote)
    {
        $fee = $this->_helper->getInsuranceValue();
        $subTotal = $quote->getSubtotal();
        return ($subTotal * $fee) / 100;
    }
}