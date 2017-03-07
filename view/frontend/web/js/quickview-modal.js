define([
    'jquery',
    'underscore',
    'uiComponent',
    'ko'
], function ($, _, Component, ko) {
    'use strict';

    var root;

    return Component.extend({
        initialize: function () {
            // Store context for use in update method
            root = this;

            this.productName = ko.observable('');
            this.productPrice = ko.observable('');
            this.productSpecialPrice = ko.observable('');
            this.productSku = ko.observable('');
            this.productUrl = ko.observable('');
            this.isSalable = ko.observable('');
            this.productImages = ko.observableArray([]);

            // Default values
            this.quantity = ko.observable(1);
            this.imageIndex = ko.observable(0);

            this._super();
        },

        getIsSalable: function () {
            // Return boolean value for isSalable for use in ko binding
            return (this.isSalable() === '1') ? true : false;
        },

        incrementQty: function () {
            this.quantity(this.quantity() + 1);
        },

        decrementQty: function () {
            if ( this.quantity() > 1 ) {
                this.quantity(this.quantity() - 1);
            }
        },

        thumbnailSelect: function (model, node) {
            root.imageIndex($(node.delegateTarget).index());
        },

        update: function (data) {
            // Called from quickview.js, on XHR response
            var images = [], index = 0;

            _.each(data.gallery.images, function (image) {
                images.push({
                    'index': index++,
                    'file': image.file,
                    'thumbnail': image.thumbnail,
                    'label': image.label
                });
            });

            root.productImages(images);
            root.productName(data.name);
            root.productPrice(data.price);
            root.productSpecialPrice(data.special_price);
            root.productSku(data.sku);
            root.productUrl(data.product_url);
            root.isSalable(data.is_salable);

            // Defaults
            root.imageIndex(0);
            root.quantity(1);
        },
    });
});
