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

            this._super();
        },

        getIsSalable: function () {
            // Return boolean value for isSalable for use in ko binding
            return (this.isSalable() === '1') ? true : false;
        },

        update: function (data) {
            // Called from quickview.js on XHR response
            root.productName(data.name);
            root.productSku(data.sku);
            root.isSalable(data.is_salable);
        },
    });
});