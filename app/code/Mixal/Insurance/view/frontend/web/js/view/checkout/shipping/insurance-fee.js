define([
        'ko',
        'uiComponent',
        'Magento_Checkout/js/model/quote',
        'Magento_Catalog/js/price-utils'
       ], function (ko, Component, quote, priceUtils) {
        'use strict';
        var show_hide_insurance = window.checkoutConfig.show_hide_insurance_shipblock;
        var fee_label = window.checkoutConfig.fee_label;
        var insurance_amount = window.checkoutConfig.insurance_amount;
        return Component.extend({
            defaults: {
                template: 'Mixal_Insurance/checkout/shipping/insurance-fee'
            },
            canVisibleInsuranceBlock: show_hide_insurance,
            getFormattedPrice: ko.observable(priceUtils.formatPrice(insurance_amount, quote.getPriceFormat())),
            getFeeLabel:ko.observable(fee_label)
        });
    });
