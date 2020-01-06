/**
 * Date Enhancements
 */

/**
 * Adds getWeekNumber to date objects
 * The ISO-8601 week of year number if the date
 * 
 * @return int 
 */
Date.prototype.getWeekNumber = function() {
    var d = new Date(+this);
    d.setHours(0,0,0);
    d.setDate(d.getDate()+4-(d.getDay()||7));
    return Math.ceil((((d-new Date(d.getFullYear(),0,1))/8.64e7)+1)/7);
};

/**
 * Debug output
 */
Date.prototype.log = function() {
    console.log(this.constructor.toString().split(' ')[1].replace('()', '').toLowerCase() + '('+ this.toLocaleString().length +'): ' + this.toLocaleString());
};
