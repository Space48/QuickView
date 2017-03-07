define([
    'jquery',
    'Space48_QuickView/js/lib/jquery.colorbox-min',
    'Space48_QuickView/js/quickview-modal'
], function($, colorbox, quickView) {

    'use strict';

    return function (config, node) {

        var $quickView = $('.js-quickview-modal');
        var quickViewComponent = quickView();
        var loading;

        $(config.buttonSelector).on('click', function() {

            var url = $(this).data('quickview-url');

            if (loading) {
                return;
            }

            loading = true;

            $.ajax(url, {
                method: 'POST',
                success: function (response) {
                    loading = false;
                    quickViewComponent.update(response);
                    $.colorbox({
                        inline: true,
                        href: $quickView,
                        opacity: config.overlayOpacity,
                        closeButton: config.closeButton,
                        width: config.width,
                        maxWidth: config.maxWidth
                    });
                },
                error: function () {
                    loading = false;
                }
            });
        });
    };
});
