define(function(require) {
        //requirements
        var Select = require('kit/select/select');

        return Select.extend({
            templates: {
                index: require('tpl!./select_units.html')
            }
        });
    }
);