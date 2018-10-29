<?php

namespace Itransition\ShipmentInsurance\Plugin\Checkout\Model;

use Magento\Checkout\Model\Session;

class ShippingInformationManagement
{
    /**
     * @var \Magento\Quote\Model\QuoteRepository
     */
    protected $quoteRepository;

    /**
     * @var \Itransition\ShipmentInsurance\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var Session
     */
    protected $_checkoutSession;

    /**
     * @param \Magento\Quote\Model\QuoteRepository $quoteRepository
     * @param \Itransition\ShipmentInsurance\Helper\Data $dataHelper
     * @param Session $checkoutSession
     */
    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository,
        \Itransition\ShipmentInsurance\Helper\Data $dataHelper,
        Session $checkoutSession
    )
    {
        $this->quoteRepository = $quoteRepository;
        $this->dataHelper = $dataHelper;
        $this->_checkoutSession = $checkoutSession;
    }

    public function getCheckoutSession()
    {
        return $this->_checkoutSession;
    }

    /**
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    )
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

        $insuranceFee = $addressInformation->getExtensionAttributes()->getFee();
        $quote = $this->quoteRepository->getActive($cartId);
        if ($insuranceFee) {
            $fee = $this->dataHelper->getInsuranceValue($shippingMethod);
            $quote->setFee($fee);
        } else {
            $quote->setFee(NULL);
        }
    }
}
