define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'suppliers',
        collections: {
            suppliers: require('collections/suppliers/suppliers')
        },
        blocks: {
            modal_supplier: require('blocks/modal/supplier/supplier'),
            supplierList: require('blocks/supplierList/supplierList')
        }
    });
});