define(function(require) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        __name__: 'page_error_global',
        response: null,
        templates: {
            '#content': require('tpl!./templates/global.html')
        }
    });
});