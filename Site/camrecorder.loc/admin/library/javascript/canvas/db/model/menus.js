/**
 * Menus
 */
var Menu = DBSync.createModel('Menu', 'id', '/rest/menus', {
	id : null,
	fk_clients : null,
	fk_groups : null,
	fk_users : null,
	grid : 20,
	name : 'Menu',
	background : '__background-white.wmv',
	itemdata : null,
	preview : null,
	created : null,
	modified : null
});

var Menus = DBSync.createCollection('Menus', '/rest/menus', Menu);
