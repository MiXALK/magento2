<?php

namespace Mixal\Insurance\Plugin\Checkout\Model;

class ShippingInformationManagement
{
    /**
     * @var \Magento\Quote\Model\QuoteRepository
     */
    protected $quoteRepository;

    /**
     * @var \Mixal\Insurance\Helper\Data
     */
    protected $dataHelper;

    /**
     * @param \Magento\Quote\Model\QuoteRepository $quoteRepository
     * @param \Mixal\Insurance\Helper\Data $dataHelper
     */
    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository,
        \Mixal\Insurance\Helper\Data $dataHelper
    )
    {
        $this->quoteRepository = $quoteRepository;
        $this->dataHelper = $dataHelper;
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
        $customFee = $addressInformation->getExtensionAttributes()->getFee();
        $quote = $this->quoteRepository->getActive($cartId);
        if ($customFee) {
            $fee = $this->dataHelper->getInsuranceValue();
            $quote->setFee($fee);
        } else {
            $quote->setFee(NULL);
        }
    }
}

