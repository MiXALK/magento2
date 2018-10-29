<?php

namespace Itransition\ShipmentInsurance\Model\Calculation\Calculator;

use Magento\Quote\Model\Quote;

interface CalculatorInterface
{
    /**
     * Calculate fee for quote
     *
     * @param Quote $quote
     * @param string $shippingMethod
     * @return float
     */
    public function calculate(Quote $quote, $shippingMethod = null);
}