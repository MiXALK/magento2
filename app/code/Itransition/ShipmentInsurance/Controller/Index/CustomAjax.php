<?php

namespace Itransition\ShipmentInsurance\Controller\Index;

use Itransition\ShipmentInsurance\Helper\Data as ShipmentHelper;
use Itransition\ShipmentInsurance\Model\Calculation\Calculator\CalculatorInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Checkout\Model\Session;

class CustomAjax extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;

    /**
     * @var ShipmentHelper
     */
    protected $helper;

    /**
     * @var CalculatorInterface
     */
    protected $calculator;

    /**
     * @var Session
     */
    protected $_checkoutSession;

    /**
     * @param Context $context
     * @param JsonFactory $resultPageFactory
     * @param CalculatorInterface $calculator
     * @param ShipmentHelper $helper
     * @param Session $_checkoutSession
     */
    public function __construct(Context $context,
                                JsonFactory $resultPageFactory,
                                ShipmentHelper $helper,
                                CalculatorInterface $calculator,
                                Session $_checkoutSession
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->helper = $helper;
        $this->calculator = $calculator;
        $this->_checkoutSession = $_checkoutSession;
    }

    public function getCheckoutSession()
    {
        return $this->_checkoutSession;
    }

    public function execute()
    {
        if ($this->getRequest()->isAjax()) {
            $shippingMethod = $this->getRequest()->getParam('method');

            //set selected shipping method to session
            $this->getCheckoutSession()->setMyValue($shippingMethod);
            $result = $this->resultPageFactory->create();
            $custom_values = [];
            if ($shippingMethod) {
                $quote = $this->getCheckoutSession()->getQuote();
                $custom_values = Array
                (
                    'show_hide_insurance' => $this->helper->isEnable($shippingMethod),
                    'insurance_amount' => $this->calculator->calculate($quote, $shippingMethod),
                    'insurance_label' => $this->helper->getFeeLabel(),
                );
            }
            return $result->setData($custom_values);
        }
    }
}
