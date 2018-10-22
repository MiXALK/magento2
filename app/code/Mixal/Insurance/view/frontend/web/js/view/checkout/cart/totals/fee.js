define([
    'ko',
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'Magento_Catalog/js/price-utils',
    'Magento_Checkout/js/model/totals'

], function (ko, Component, quote, priceUtils, totals) {
    'use strict';
    var show_hide_insurance = window.checkoutConfig.show_hide_insurance;
    var fee_label = window.checkoutConfig.fee_label;
    var insurance_amount = window.checkoutConfig.insurance_amount;

    return Component.extend({

        totals: quote.getTotals(),
        canVisibleCustomFeeBlock: show_hide_insurance,
        getFormattedPrice: ko.observable(priceUtils.formatPrice(insurance_amount, quote.getPriceFormat())),
        getFeeLabel:ko.observable(fee_label),
        isDisplayed: function () {
            return this.getValue() != 0;
        },
        getValue: function() {
            var price = 0;
            if (this.totals() && totals.getSegment('fee')) {
                price = totals.getSegment('fee').value;
            }
            return price;
        }
    });
});
