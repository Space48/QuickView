define([
    'jquery',
    'uiComponent',
    'ko'
], function ($, Component, ko) {
    'use strict';

    var root;

    return Component.extend({
        initialize: function () {
            root = this;
            this.productName = ko.observable('');
            this.productSku = ko.observable('');
            this._super();
        },

        update: function (data) {
            root.productName(data.name);
            root.productSku(data.sku);
        },
    });
});