/**
 * Number Enhancements
 */

/**
 * Debug output
 */
Number.prototype.log = function() {
    console.log(this.constructor.toString().split(' ')[1].replace('()', '').toLowerCase() + '('+ this.toString().length +'): ' + this.toString());
};
