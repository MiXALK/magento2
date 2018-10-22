<?php

namespace Mixal\Insurance\Model\Calculation;

use Magento\Framework\Exception\ConfigurationMismatchException;
use Magento\Framework\ObjectManagerInterface;
use Mixal\Insurance\Helper\Data as ShipmentHelper;
use Mixal\Insurance\Model\Config\Source\PriceType;

class CalculatorFactory
{
    /**
     * @var ShipmentHelper
     */
    protected $helper;

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * CalculationFactory constructor.
     *
     * @param ObjectManagerInterface $objectManager
     * @param ShipmentHelper $helper
     */
    public function __construct(ObjectManagerInterface $objectManager, ShipmentHelper $helper)
    {
        $this->helper = $helper;
        $this->objectManager = $objectManager;
    }

    /**
     * @return Calculator\CalculatorInterface
     * @throws ConfigurationMismatchException
     */
    public function get()
    {
        switch ($this->helper->getPriceType()) {
            case PriceType::TYPE_FIXED:
                return $this->objectManager->get(Calculator\FixedCalculator::class);
            case PriceType::TYPE_PERCENTAGE:
                return $this->objectManager->get(Calculator\PercentageCalculator::class);
            default:
                throw new ConfigurationMismatchException(__('Could not find price calculator for type %1', $this->helper->getPriceType()));
        }
    }
}
