define([
    'jquery',
    'underscore',
    'uiComponent',
    'mage/cookies',
    'ko'
], function ($, _, Component, cookies, ko) {
    'use strict';

    var root;

    return Component.extend({
        initialize: function () {
            var formKey = $.mage.cookies.get('form_key');

            // Store context for use in update method
            root = this;

            this.productName = ko.observable('');
            this.breadcrumb = ko.observableArray([]);
            this.productPrice = ko.observable('');
            this.productSpecialPrice = ko.observable('');
            this.productSku = ko.observable('');
            this.productUrl = ko.observable('');
            this.productType = ko.observable('');
            this.isSalable = ko.observable('');
            this.productImages = ko.observableArray([]);
            this.addToCartAction = ko.observable('');
            this.formKey = ko.observable(formKey);
            this.specialData = ko.observable({});

            // Default values
            this.quantity = ko.observable(1);
            this.imageIndex = ko.observable(0);

            this._super();
        },

        getIsSalable: function () {
            // Return boolean value for isSalable for use in ko binding
            return (this.isSalable() === '1') ? true : false;
        },

        getIsGrouped: function () {
            return (this.productType() === 'grouped') ? true : false;
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

        getGalleryImages: function (images) {
            var imagesArray = [];
            var index = 0;

            _.each(images, function (image) {
                imagesArray.push({
                    'index': index++,
                    'file': image.file,
                    'thumbnail': image.thumbnail,
                    'label': image.label
                });
            });

            return imagesArray;
        },

        getBreadcrumb: function (breadcrumbs) {
            var breadcrumbArray = [];

            if (breadcrumbs.home) {
                // Ensure 'home' is first item
                breadcrumbArray.push(breadcrumbs.home);
            }

            _.each(breadcrumbs, function (breadcrumb, key) {
                // Add rest of 'category' items
                if (key !== 'home' && key !== 'product') {
                    breadcrumbArray.push(breadcrumb);
                }
            });

            return breadcrumbArray;
        },

        update: function (data) {
            // Called from quickview.js, on XHR response
            var images = this.getGalleryImages(data.gallery.images);
            var breadcrumb = this.getBreadcrumb(data.breadcrumb);

            root.productImages(images);
            root.breadcrumb(breadcrumb);
            root.productName(data.name);
            root.productPrice(data.price);
            root.productSpecialPrice(data.special_price);
            root.productSku(data.sku);
            root.productUrl(data.product_url);
            root.productType(data.product_type);
            root.isSalable(data.is_salable);
            root.addToCartAction(data.add_to_cart.action);
            root.specialData(data.special_data);

            // Set some defaults on every update
            root.imageIndex(0);
            root.quantity(1);
        },
    });
});
