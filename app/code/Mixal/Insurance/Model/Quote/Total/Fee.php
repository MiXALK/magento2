<?php

namespace Mixal\Insurance\Model\Quote\Total;

use Magento\Quote\Model\QuoteValidator;
use Mixal\Insurance\Helper\Data as ShipmentHelper;
use Mixal\Insurance\Model\Calculation\Calculator\CalculatorInterface;

class Fee extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{

    protected $helperData;

    protected $quoteValidator = null;

    /**
     * @var CalculatorInterface
     */
    protected $calculator;

    /**
     * @param QuoteValidator $quoteValidator
     * @param ShipmentHelper $helperData
     * @param CalculatorInterface $calculator
     */
    public function __construct(QuoteValidator $quoteValidator, ShipmentHelper $helperData, CalculatorInterface $calculator)
    {
        $this->quoteValidator = $quoteValidator;
        $this->helperData = $helperData;
        $this->calculator = $calculator;
    }

    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    )
    {
        parent::collect($quote, $shippingAssignment, $total);
        if (!count($shippingAssignment->getItems())) {
            return $this;
        }

        $enabled = $this->helperData->isEnable();
        if ($enabled) {
            $fee = $quote->getFee() ? $this->calculator->calculate($quote) : NULL;
            $total->setTotalAmount('fee', $fee);
            $total->setBaseTotalAmount('fee', $fee);
            $total->setFee($fee);
            $total->setBaseFee($fee);
            $quote->setFee($fee);
            $quote->setBaseFee($fee);
            $total->setGrandTotal($total->getGrandTotal());
            $total->setBaseGrandTotal($total->getBaseGrandTotal());
        }
        return $this;
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return array
     */
    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {

        $enabled = $this->helperData->isEnable();
        $fee = $quote->getFee() ? $this->calculator->calculate($quote) : NULL;
        $result = [];
        if ($enabled && $fee) {
            $result = [
                'code' => 'fee',
                'title' => $this->helperData->getFeeLabel(),
                'value' => $fee
            ];
        }
        return $result;
    }

    /**
     * Get Subtotal label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __('Shipment Insurance');
    }

    /**
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     */
    protected function clearValues(\Magento\Quote\Model\Quote\Address\Total $total)
    {
        $total->setTotalAmount('subtotal', 0);
        $total->setBaseTotalAmount('subtotal', 0);
        $total->setTotalAmount('tax', 0);
        $total->setBaseTotalAmount('tax', 0);
        $total->setTotalAmount('discount_tax_compensation', 0);
        $total->setBaseTotalAmount('discount_tax_compensation', 0);
        $total->setTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setBaseTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setSubtotalInclTax(0);
        $total->setBaseSubtotalInclTax(0);

    }
}
