<?php

namespace Itransition\ShipmentInsurance\Model\Quote\Total;

use Magento\Quote\Model\QuoteValidator;
use Itransition\ShipmentInsurance\Helper\Data as ShipmentHelper;
use Itransition\ShipmentInsurance\Model\Calculation\Calculator\CalculatorInterface;
use Magento\Checkout\Model\Session;
use Magento\Quote\Model\Quote;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote\Address\Total;
use \Magento\Quote\Model\Quote\Address\Total\AbstractTotal;

class Fee extends AbstractTotal
{
    /**
     * @var ShipmentHelper
     */
    protected $helperData;

    /**
     * @var QuoteValidator
     */
    protected $quoteValidator = null;

    /**
     * @var CalculatorInterface
     */
    protected $calculator;

    /**
     * @var Session
     */
    protected $_checkoutSession;

    /**
     * @param QuoteValidator $quoteValidator
     * @param ShipmentHelper $helperData
     * @param CalculatorInterface $calculator
     * @param Session $checkoutSession
     */
    public function __construct(QuoteValidator $quoteValidator,
                                ShipmentHelper $helperData,
                                CalculatorInterface $calculator,
                                Session $checkoutSession)
    {
        $this->quoteValidator = $quoteValidator;
        $this->helperData = $helperData;
        $this->calculator = $calculator;
        $this->_checkoutSession = $checkoutSession;
    }

    public function getCheckoutSession()
    {
        return $this->_checkoutSession;
    }

    /**
     * @param Quote $quote
     * @param ShippingAssignmentInterface $shippingAssignment
     * @param Total $total
     * @return $this
     */
    public function collect(Quote $quote, ShippingAssignmentInterface $shippingAssignment, Total $total)
    {
        parent::collect($quote, $shippingAssignment, $total);
        if (!count($shippingAssignment->getItems())) {
            return $this;
        }

        $data = $this->getCheckoutSession()->getMyValue();
        if ($data != null){
            $shippingMethod = $data;
        }
        else {
            $shippingMethod = $this->getCheckoutSession()->getQuote()->getShippingAddress()->getShippingMethod();
            $data = explode('_', $shippingMethod);
            $shippingMethod = $data[0];
        }

        $enabled = $this->helperData->isEnable($shippingMethod);
        if ($enabled) {
            $fee = $quote->getFee() ? $this->calculator->calculate($quote, $shippingMethod) : NULL;
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
     * @param Quote $quote
     * @param Total $total
     * @return array
     */
    public function fetch(Quote $quote, Total $total)
    {
        $data = $this->getCheckoutSession()->getMyValue();
        if ($data != null){
            $shippingMethod = $data;
        }
        else {
            $shippingMethod = $this->getCheckoutSession()->getQuote()->getShippingAddress()->getShippingMethod();
            $data = explode('_', $shippingMethod);
            $shippingMethod = $data[0];
        }

        $enabled = $this->helperData->isEnable($shippingMethod);
        $fee = $quote->getFee() ? $this->calculator->calculate($quote, $shippingMethod) : NULL;
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
     * @param Total $total
     */
    protected function clearValues(Total $total)
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
