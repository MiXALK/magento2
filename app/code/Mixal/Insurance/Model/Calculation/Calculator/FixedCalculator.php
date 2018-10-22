<?php

namespace Mixal\Insurance\Model\Calculation\Calculator;

use Magento\Quote\Model\Quote;

class FixedCalculator extends AbstractCalculator
{
    public function calculate(Quote $quote)
    {
        return $this->_helper->getInsuranceValue();
    }
}
