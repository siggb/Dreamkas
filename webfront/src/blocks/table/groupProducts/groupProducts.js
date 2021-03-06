define(function(require, exports, module) {
    //requirements
    var Table = require('blocks/table/table');

    return Table.extend({
        template: require('ejs!./template.ejs'),
        sortBy: 'name',
        collection: function(){
            return PAGE.collections.groupProducts;
        }
    });
});