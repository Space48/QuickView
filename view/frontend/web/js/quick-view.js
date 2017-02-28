define([
    'jquery',
    'Magento_Ui/js/modal/modal'
], function($) {
    'use strict';

    return function (optionsConfig) {
        var quickView = $('<div/>').html(optionsConfig.html).modal({
            modalClass: 'quickview-modal',
            // title: $.mage.__('Quick View'),
            type: 'popup',
            buttons: [{
                text: 'Ok',
                click: function () {
                    this.closeModal();
                }
            }]
        });
        $('.js-modal-open').on('click', function() {
            $.ajax(
                'http://local.quickview2.com/breathe-easy-tank.html?uenc=true',
                {
                    method: 'POST'
                }
            );
            quickView.modal('openModal');
        });
    };
});