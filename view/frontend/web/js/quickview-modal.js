define([
    'jquery',
    'uiComponent',
    'ko'
], function ($, Component, ko) {
    'use strict';

    var root;

    return Component.extend({
        initialize: function () {
            // Store context for use in update method
            root = this;

            this.productName = ko.observable('');
            this.productSku = ko.observable('');
            this.isSalable = ko.observable('');
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
            this.imageIndex($(node.delegateTarget).index());
        },

        update: function (data) {
            // Called from quickview.js, on XHR response
            root.productName(data.name);
            root.productSku(data.sku);
            root.isSalable(data.is_salable);

            // Defaults
            root.imageIndex(0);
            root.quantity(1);
        },
    });
});
