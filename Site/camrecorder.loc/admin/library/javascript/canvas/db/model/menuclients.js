/**
 * Menuclients
 */
var Menuclient = DBSync.createModel('Menuclient', 'id', '/dal/rest/menuclients', {
	id : null
});

var Menuclients = DBSync.createCollection('Menuclients', '/dal/rest/menuclients', Menuclient);
