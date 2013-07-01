define(function(require) {
    //requirements
    var Page = require('pages/page'),
        Product = require('blocks/product/product'),
        ProductModel = require('models/product');

    return Page.extend({
        pageName: 'page_product_view',
        templates: {
            '#content': require('tpl!./templates/view.html')
        },
        permissions: {
            products: 'get'
        },
        initialize: function(productId) {
            var page = this;

            page.productId = productId;

            page.productModel = new ProductModel({
                id: page.productId
            });

            page.render();

            $.when(page.productModel.fetch()).then(function(){
                new Product({
                    model: page.productModel,
                    el: document.getElementById('product')
                });
            });
        }
    });
});