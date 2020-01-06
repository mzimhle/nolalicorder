/**
 * String Enhancements
 * @author Philip Michael Raab<philip@cathedral.co.za>
 * @version 1.3.0
 */

/**
 * Capitalises first letter of each word
 * 
 * @param lowerAsWell bool true: first lowers case
 *
 * @return string
 */
String.prototype.toTitleCase = function(lowerAsWell) {
	var string = this;
	if (lowerAsWell === true) string = string.toLowerCase();

	return string.replace(/(?:^|\s)\w/g, function(match) {
		return match.toUpperCase();
	});
};

/**
 * Replace all find with replace
 *
 * @return string
 */
String.prototype.replaceAll = function(find, replace) {
	return this.replace(new RegExp(find, 'g'), replace);
}

/***************************************************
 * TRIM
 ***************************************************/

/**
 * Trims chars from front and back of string
 * 
 * @param chars charactrs to trim of string's ends
 *
 * @return string
 */
String.prototype.trimChars = function(chars) {
	return this.replace(new RegExp('^(' + chars + ')+|(' + chars + ')+$', 'gm'), '');
}

/**
 * Trims chars from front of string
 * 
 * @param chars charactrs to trim of string's start
 *
 * @return string
 */
String.prototype.trimCharsLeft = function(chars) {
	return this.replace(new RegExp('^(' + chars + ')+', 'gm'), '');
}

/**
 * Trims chars from back of string
 * 
 * @param chars charactrs to trim of string's ends
 *
 * @return string
 */
String.prototype.trimCharsRight = function(chars) {
	return this.replace(new RegExp('(' + chars + ')+$', 'gm'), '');
}

/***************************************************
 * DEBUG
 ***************************************************/

/**
 * Debug output
 */
String.prototype.log = function() {
	console.log(this.constructor.toString().split(' ')[1].replace('()', '').toLowerCase() + '(' + this.length + '): ' + this.toString());
};
