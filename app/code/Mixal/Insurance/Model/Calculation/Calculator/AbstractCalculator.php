<?php

namespace Mixal\Insurance\Model\Calculation\Calculator;

use Mixal\Insurance\Helper\Data as InsuranceHelper;

abstract class AbstractCalculator implements CalculatorInterface
{
    /**
     * @var InsuranceHelper
     */
    protected $_helper;

    /**
     * AbstractCalculation constructor.
     *
     * @param InsuranceHelper $helper
     */
    public function __construct(InsuranceHelper $helper)
    {
        $this->_helper = $helper;
    }
}