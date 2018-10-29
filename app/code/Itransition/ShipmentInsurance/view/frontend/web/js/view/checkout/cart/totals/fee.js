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

    return Component.extend({

        totals: quote.getTotals(),
        canVisibleCustomFeeBlock: show_hide_insurance,
        getFeeLabel:ko.observable(fee_label),
        isDisplayed: function () {
            return this.getValue() != 0;
        },
        getValue: function() {
            var price = 0;
            if (this.totals() && totals.getSegment('fee')) {
                price = priceUtils.formatPrice(totals.getSegment('fee').value, quote.getPriceFormat());
            }
            return price;
        }
    });
});
