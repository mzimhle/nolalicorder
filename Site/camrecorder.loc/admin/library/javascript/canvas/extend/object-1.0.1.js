/**
 * object.watch polyfill
 *
 * @version 1.0.1
 * @author Philip Michael Raab<peep@cathedral.co.za>
 *
 * Public Domain.
 * NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.
 */

/*
 * 1.0.1 (2016 Apr 08)
 *  - Fixed: oldval returns undefined after 1st change
 */

/*
o = {p: 'yyyy'};
o.watch('p', function(prop, val, oldval) { console.log('watched prop: ',prop); console.log('watched oldval: ',oldval); console.log('watched val: ',val); });
o.p = 'la de da'l
*/

// object.watch
if (!Object.prototype.watch) {
	Object.defineProperty(Object.prototype, "watch", {
		enumerable: false,
		configurable: true,
		writable: false,
		value: function(prop, handler) {
			var
				newval = oldval = this[prop],
				getter = function() {
					return newval;
				},
				setter = function(val) {
					oldval = newval;
					handler.call(this, prop, newval = val, oldval);
					return newval;
				};

			if (delete this[prop]) { // can't watch constants
				Object.defineProperty(this, prop, {
					get: getter,
					set: setter,
					enumerable: true,
					configurable: true
				});
			}
		}
	});
}

// object.unwatch
if (!Object.prototype.unwatch) {
	Object.defineProperty(Object.prototype, "unwatch", {
		enumerable: false,
		configurable: true,
		writable: false,
		value: function(prop) {
			var val = this[prop];
			delete this[prop]; // remove accessors
			this[prop] = val;
		}
	});
}
