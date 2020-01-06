/**
 * InaneJS
 *
 * @version 0.2.0
 * @author Philip Michael Raab<peep@cathedral.co.za>
 *
 * Public Domain.
 * NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.
 */

/*
 * 0.2.0 (2016 May 18)
 *  - added  : propertyOf, allKeys, pick
 *  - added  : isEqual, isObject, isFunction, isBoolean
 *  - added  : flatten
 *  = added  : UUID
 *
 * 0.1.0 (2016 Apr 08)
 *  - Initial: each
 */

(function () {
    'use strict';

    var root = this;

    var I = function (obj) {
        if (obj instanceof I)
            return obj;
        if (!(this instanceof I))
            return new I(obj);
        this.iwrapped = obj;
    };

    root.I = I;

    I.VERSION = '0.2.0';

    /**
     * Helpes
     */
    var each = Array.prototype.forEach;
    var toString = Object.prototype.toString;

    // Internal function that returns an efficient (for current engines) version
    // of the passed-in callback, to be repeatedly applied in other Underscore
    // functions.
    var optimizeCb = function (func, context, argCount) {
        if (context === void 0) return func;
        switch (argCount == null ? 3 : argCount) {
            case 1:
                return function (value) {
                    return func.call(context, value);
                };
            case 2:
                return function (value, other) {
                    return func.call(context, value, other);
                };
            case 3:
                return function (value, index, collection) {
                    return func.call(context, value, index, collection);
                };
            case 4:
                return function (accumulator, value, index, collection) {
                    return func.call(context, accumulator, value, index, collection);
                };
        }
        return function () {
            return func.apply(context, arguments);
        };
    };

    // Internal implementation of a recursive `flatten` function.
    var flatten = function (input, shallow, strict, startIndex) {
        var output = [],
            idx = 0;
        for (var i = startIndex || 0, length = getLength(input); i < length; i++) {
            var value = input[i];
            if (isArrayLike(value) && (_.isArray(value) || _.isArguments(value))) {
                //flatten current level of array or arguments object
                if (!shallow) value = flatten(value, shallow, strict);
                var j = 0,
                    len = value.length;
                output.length += len;
                while (j < len) {
                    output[idx++] = value[j++];
                }
            } else if (!strict) {
                output[idx++] = value;
            }
        }
        return output;
    };

    var property = function (key) {
        return function (obj) {
            return obj == null ? void 0 : obj[key];
        };
    };

    // Helper for collection methods to determine whether a collection
    // should be iterated as an array or as an object
    // Related: http://people.mozilla.org/~jorendorff/es6-draft.html#sec-tolength
    // Avoids a very nasty iOS 8 JIT bug on ARM-64. #2094
    var MAX_ARRAY_INDEX = Math.pow(2, 53) - 1;
    var getLength = property('length');
    var isArrayLike = function (collection) {
        var length = getLength(collection);
        return typeof length == 'number' && length >= 0 && length <= MAX_ARRAY_INDEX;
    };

    // TYPE TEST

    // Is a given variable an object?
    I.prototype.isObject = function () {
        var type = typeof this.iwrapped;
        return type === 'function' || type === 'object' && !!this.iwrapped;
    };

    I.isObject = function (obj) {
        return I(obj).isObject();
    };

    // IsFunction
    I.prototype.isFunction = function () {
        return typeof this.iwrapped == 'function' || false;
    };

    I.isFunction = function (obj) {
        return I(obj).isFunction();
    };

    // IsBoolean
    // Is a given value a boolean?
    I.prototype.isBoolean = function () {
        return this.iwrapped === true || this.iwrapped === false || toString.call(this.iwrapped) === '[object Boolean]';
    };

    I.isBoolean = function (obj) {
        return I(obj).isBoolean();
    };

    //ALLKEYS
    // Retrieve all the property names of an object.
    I.prototype.allKeys = function () {
        if (!I.isObject(this.iwrapped)) return [];
        var keys = [];
        for (var key in this.iwrapped) keys.push(key);
        return keys;
    };

    I.allKeys = function (obj) {
        return I(obj).allKeys();
    };

    // PICK
    // Return a copy of the object only containing the whitelisted properties.
    I.prototype.pick = function (oiteratee, context) {
        var result = {},
            obj = this.iwrapped,
            iteratee, keys;
        if (obj == null) return result;
        if (I.isFunction(oiteratee)) {
            keys = I.allKeys(obj);
            iteratee = optimizeCb(oiteratee, context);
        } else {
            keys = flatten(oiteratee, false, false, 0);
            iteratee = function (value, key, obj) {
                return key in obj;
            };
            obj = Object(obj);
        }
        for (var i = 0, length = keys.length; i < length; i++) {
            var key = keys[i];
            var value = obj[key];
            if (iteratee(value, key, obj)) result[key] = value;
        }
        return result;
    };

    I.pick = function (object, oiteratee, context) {
        return I(object).pick(oiteratee, context);
    };

    // FLATTEN
    // Flatten out an array, either recursively (by default), or just one level.
    I.prototype.flatten = function (shallow) {
        return flatten(this.iwrapped, shallow, false);
    };

    I.flatten = function (array, shallow) {
        return I(array).flatten(shallow);
    };

    // IsEQUALE
    // Internal recursive comparison function for `isEqual`.
    var eq = function (a, b, aStack, bStack) {
        // Identical objects are equal. `0 === -0`, but they aren't identical.
        // See the [Harmony `egal` proposal](http://wiki.ecmascript.org/doku.php?id=harmony:egal).
        if (a === b) return a !== 0 || 1 / a === 1 / b;
        // A strict comparison is necessary because `null == undefined`.
        if (a == null || b == null) return a === b;
        // Unwrap any wrapped objects.
        if (a instanceof _) a = a._wrapped;
        if (b instanceof _) b = b._wrapped;
        // Compare `[[Class]]` names.
        var className = toString.call(a);
        if (className !== toString.call(b)) return false;
        switch (className) {
            // Strings, numbers, regular expressions, dates, and booleans are compared by value.
            case '[object RegExp]':
            // RegExps are coerced to strings for comparison (Note: '' + /a/i === '/a/i')
            case '[object String]':
                // Primitives and their corresponding object wrappers are equivalent; thus, `"5"` is
                // equivalent to `new String("5")`.
                return '' + a === '' + b;
            case '[object Number]':
                // `NaN`s are equivalent, but non-reflexive.
                // Object(NaN) is equivalent to NaN
                if (+a !== +a) return +b !== +b;
                // An `egal` comparison is performed for other numeric values.
                return +a === 0 ? 1 / +a === 1 / b : +a === +b;
            case '[object Date]':
            case '[object Boolean]':
                // Coerce dates and booleans to numeric primitive values. Dates are compared by their
                // millisecond representations. Note that invalid dates with millisecond representations
                // of `NaN` are not equivalent.
                return +a === +b;
        }

        var areArrays = className === '[object Array]';
        if (!areArrays) {
            if (typeof a != 'object' || typeof b != 'object') return false;

            // Objects with different constructors are not equivalent, but `Object`s or `Array`s
            // from different frames are.
            var aCtor = a.constructor,
                bCtor = b.constructor;
            if (aCtor !== bCtor && !(_.isFunction(aCtor) && aCtor instanceof aCtor &&
                _.isFunction(bCtor) && bCtor instanceof bCtor) && ('constructor' in a && 'constructor' in b)) {
                return false;
            }
        }
        // Assume equality for cyclic structures. The algorithm for detecting cyclic
        // structures is adapted from ES 5.1 section 15.12.3, abstract operation `JO`.

        // Initializing stack of traversed objects.
        // It's done here since we only need them for objects and arrays comparison.
        aStack = aStack || [];
        bStack = bStack || [];
        var length = aStack.length;
        while (length--) {
            // Linear search. Performance is inversely proportional to the number of
            // unique nested structures.
            if (aStack[length] === a) return bStack[length] === b;
        }

        // Add the first object to the stack of traversed objects.
        aStack.push(a);
        bStack.push(b);

        // Recursively compare objects and arrays.
        if (areArrays) {
            // Compare array lengths to determine if a deep comparison is necessary.
            length = a.length;
            if (length !== b.length) return false;
            // Deep compare the contents, ignoring non-numeric properties.
            while (length--) {
                if (!eq(a[length], b[length], aStack, bStack)) return false;
            }
        } else {
            // Deep compare objects.
            var keys = _.keys(a),
                key;
            length = keys.length;
            // Ensure that both objects contain the same number of properties before comparing deep equality.
            if (_.keys(b).length !== length) return false;
            while (length--) {
                // Deep compare each member
                key = keys[length];
                if (!(_.has(b, key) && eq(a[key], b[key], aStack, bStack))) return false;
            }
        }
        // Remove the first object from the stack of traversed objects.
        aStack.pop();
        bStack.pop();
        return true;
    };

    // Perform a deep comparison to check if two objects are equal.
    I.prototype.isEqual = function (b) {
        return eq(this.iwrapped, b);
    };

    I.isEqual = function (a, b) {
        return eq(a, b);
    };

    // EACH
    I.prototype.each = function (_callback) {
        each.call(this.iwrapped, _callback);
    };

    I.each = function (obj, _callback) {
        I(obj).each(_callback);
    };

    // propertyOf
    // Generates a function for a given object that returns a given property.
    I.prototype.propertyOf = function (key) {
        return this.iwrapped[key];
    };

    I.propertyOf = function (obj) {
        return obj == null ? function () { } : function (key) {
            return obj[key];
        };
    };

    // WITHOUT
    /**
     * Removes all instances of values from array and returns new array
     *
     * @param array		Source array to remove values from
     * @param values	String or Array with values to remove
     *
     * @return array
     */
    I.prototype.without = function (values) {
        if (!Array.isArray(values)) values = [values];
        return this.iwrapped.filter(function (element) {
            return values.indexOf(element) < 0;
        });
    }

    I.without = function (array, values) {
        return I(array).without(values);
    };

    // GETURL
    var getCache = [];

    I.getUrl = function (url, skipcache) {
        var useCache = (skipcache !== true) ? true : false;

        var cache = getCache.find(function (uc, index) {
            if (uc.url == url) {
                uc.index = index;
                return uc;
            }
        });

        if (cache != undefined) {
            if (useCache) {
                cache.hits++;

                return new Promise(function (resolve, reject) {
                    resolve(cache.data);
                });
            }
        } else {
            cache = {
                url: url,
                data: false,
                hits: 0,
                index: getCache.length
            };
        }

        return new Promise(function (resolve, reject) {
            var req = new XMLHttpRequest();
            req.open('GET', url);
            req.onload = function () {
                if (req.status == 200) {
                    cache.data = req.response;

                    if (cache.data === false)
                        getCache.push(cache);
                    else
                        getCache[cache.index] = cache;
                    resolve(req.response);
                } else {
                    reject(req.statusText);
                }
            };

            req.onerror = function () {
                reject("Network Error");
            };

            req.send();
        });
    }

    I.UUID = function () {
        var d = new Date().getTime();
        var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            var r = (d + Math.random() * 16) % 16 | 0;
            d = Math.floor(d / 16);
            return (c == 'x' ? r : (r & 0x3 | 0x8)).toString(16);
        });
        return uuid;
    };

}).call(this);
