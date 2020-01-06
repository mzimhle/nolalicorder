/**
 * cssFonts
 */
var cssFont = DBSync.createModel('cssFont', 'family', '', {
	family : null,
	cssClass : null,
	cssFile : null
});

var cssFonts = DBSync.createCollection('cssFonts', '', cssFont);
