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

            var url = $(this).data('quickview-url');

            $.ajax(url, {
                method: 'POST',
                success: function (response) {
                    quickViewComponent.update({
                        name: response.name,
                        sku: response.sku,
                        is_salable: response.is_salable
                    });
                    $.colorbox({
                        inline: true,
                        href: $quickView,
                        opacity: config.overlayOpacity,
                        closeButton: config.closeButton,
                        width: config.width,
                        maxWidth: config.maxWidth
                    });
                }
            });
        });
    };
});
