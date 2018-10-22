<?php

namespace Mixal\Insurance\Model\Calculation;

use Magento\Framework\Exception\ConfigurationMismatchException;
use Magento\Quote\Model\Quote;
use Mixal\Insurance\Helper\Data as ShipmentHelper;
use Mixal\Insurance\Model\Calculation\Calculator\CalculatorInterface;
use Psr\Log\LoggerInterface;

/**
 * Class CalculationService acts as wrapper around actual CalculatorInterface so logic valid for all calculations.
 *
 * @package Mixal\Insurance\Model\Calculation
 */
class CalculationService implements CalculatorInterface
{
    /**
     * @var CalculatorFactory
     */
    protected $factory;

    /**
     * @var ShipmentHelper
     */
    protected $helper;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * CalculationService constructor.
     * @param CalculatorFactory $factory
     * @param ShipmentHelper $helper
     * @param LoggerInterface $logger
     */
    public function __construct(CalculatorFactory $factory, ShipmentHelper $helper, LoggerInterface $logger)
    {
        $this->factory = $factory;
        $this->helper = $helper;
        $this->logger = $logger;
    }

    public function calculate(Quote $quote)
    {
        // if module not enabled the fee is 0.0
        if (!$this->helper->isEnable()) {
            return 0.0;
        }

        try {
            return $this->factory->get()->calculate($quote);
        } catch (ConfigurationMismatchException $e) {
            $this->logger->error($e);
            return 0.0;
        }
    }
}
