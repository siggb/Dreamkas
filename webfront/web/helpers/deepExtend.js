define(
    [
        'basis/utils/typeof'
    ],
    function(typeOf) {

    var deepExtend = function(){
        var options, name, src, copy, clone,
            target = arguments[0] || {},
            i = 1,
            length = arguments.length;

        for (; i < length; i++) {
            // Only deal with non-null/undefined values
            if ((options = arguments[i]) != null) {
                // Extend the base object
                for (name in options) {
                    src = target[name];
                    copy = options[name];

                    // Prevent never-ending loop
                    if (target === copy) {
                        continue;
                    }

                    // Recurse if we're merging plain objects or arrays
                    if (copy && (typeOf(copy) == 'object')) {

                        clone = src || {};

                        // Never move original objects, clone them
                        target[name] = deepExtend(clone, copy);

                        // Don't bring in undefined values
                    } else if (copy !== undefined) {
                        target[name] = copy;
                    }
                }
            }
        }

        // Return the modified object
        return target;
    };

    return deepExtend;

});