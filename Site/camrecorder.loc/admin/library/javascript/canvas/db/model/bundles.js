/**
 * Bundles
 */
var Bundle = DBSync.createModel('Bundle', 'id', '/dal/rest/bundles', {
	id : null,
	fk_clients : null,
	fk_groups : null,
	fk_users : null,
	format : null,
	version : null,
	menus : null,
	bundle : null,
	modified : null
});

var Bundles = DBSync.createCollection('Bundles', '/dal/rest/bundles', Bundle);
