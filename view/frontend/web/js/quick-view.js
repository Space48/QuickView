define([
    'jquery',
    'Magento_Ui/js/modal/modal'
], function($) {
    'use strict';

    return function (config, node) {
        var $quickView = $('.js-quickview-modal');
        var productId = $(node).data('product-id');

        $quickView.modal({
            modalClass: 'quickview-modal',
            type: 'popup',
            buttons: [{
                text: 'Ok',
                click: function () {
                    this.closeModal();
                }
            }]
        });

        $(node).on('click', function() {
            $.ajax(
                config.urls[productId],{
                    method: 'POST',
                    success: function (response) {
                        console.log(response);
                        $quickView.html(JSON.stringify(response));
                        $quickView.modal('openModal');
                    }
                }
            );
        });
    };
});