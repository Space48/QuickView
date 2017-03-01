define([
    'jquery',
    'Space48_QuickView/js/lib/jquery.colorbox-min',
    'Space48_QuickView/js/quickview-modal'
], function($, colorbox, quickView) {

    'use strict';

    return function (config, node) {
        var $quickView = $('.js-quickview-modal');
        var quickViewComponent = quickView();

        $(config.buttonSelector).on('click', function() {
            var productId = $(this).data('product-id');
            $.ajax(
                config.urls[productId],{
                    method: 'POST',
                    success: function (response) {
                        quickViewComponent.update({
                            name: response.name,
                            sku: response.sku
                        });
                        $.colorbox({
                            inline: true,
                            href: $quickView
                        });
                    }
                }
            );
        });
    };
});