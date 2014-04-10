define(function(require) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        InvoicesCollection = require('collections/invoices'),
        Form_invoiceSearch = require('blocks/form/form_invoiceSearch/form_invoiceSearch'),
        currentUserModel = require('models/currentUser'),
        Page403 = require('pages/errors/403');

    return Page.extend({
        __name__: 'page_invoice_list',
        partials: {
            '#content': require('tpl!./templates/search.html')
        },
        initialize: function(){
            var page = this;

            if (currentUserModel.stores.length){
                page.params.storeId = currentUserModel.stores.at(0).id;
            }

            if (!page.params.storeId){
                new Page403();
                return;
            }

            page.invoicesCollection = new InvoicesCollection();
            page.invoicesCollection.storeId = page.params.storeId;

            page.render();

            new Form_invoiceSearch({
                el: document.getElementById('form_invoiceSearch'),
                invoicesCollection: page.invoicesCollection
            });
        }
    });
});