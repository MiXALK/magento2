define([
    'ko',
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'Magento_Catalog/js/price-utils',
    "jquery",

], function (ko, Component, quote, priceUtils, $) {
    'use strict';

    $(document).on('change', '.col-method .radio', function () {
        var method = $(".selected-method").html();
        $.ajax({
            showLoader: true,
            url: '../shipmentinsurance/index/customajax',
            data: {method: method},
            type: "POST"
        }).done(function (data) {
            var show_hide_insurance = data.show_hide_insurance,
                insurance_amount = '$' + data.insurance_amount,
                insurance_label = data.insurance_label;

            if (show_hide_insurance) {
                $(".show_hide_insurance").show();
            }
            else {
                $(".show_hide_insurance").hide();
            }

            $(".insurance-label").html(insurance_label);
            $(".insurance-amount").html(insurance_amount);
        });
    });

    return Component.extend({
        defaults: {
            template: 'Itransition_ShipmentInsurance/checkout/shipping/insurance-fee'
        },
        initObservable: function () {
            this.selectedMethod = ko.computed(function () {
                var method = quote.shippingMethod();
                var selectedMethod = method != null ? method.method_code : null;
                return selectedMethod;
            }, this);
            return this;
        },
    });
});
