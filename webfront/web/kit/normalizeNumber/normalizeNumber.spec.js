define(function(require, exports, module) {
    //requirements
    var normalizeNumber = require('./normalizeNumber');

    describe(module.id, function(){

        it('normalize number', function(){
            expect(normalizeNumber(20)).toEqual(20);
        });

        it('normalize 0', function(){
            expect(normalizeNumber(0)).toEqual(0);
        });

    });
});