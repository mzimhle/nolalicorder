/**
 * ACLRoles
 */

var ACLRole = Backbone.Model.extend({
	urlRoot : '/rest/aclroles',
	idAttribute : "id",
	debug : false,
	defaults : {
		"id" : null,
		"role" : null
	},
	initialize : function() {
		this.on("add", function(model) {
			this.log('ACLRole::Add::' + model.attributes.id);
		});
		this.on("change", function(model, state) {
			this.log('ACLRole::change::' + model.attributes.id);
		});
		this.on("remove", function(model, state) {
			this.log('ACLRole::remove::' + model.attributes.id);
		});
		this.on("sync", function(model, collection, response) {
			this.log('ACLRole::sync::' + model.attributes.id);
		});
	},
	log : function(message) {
		if (this.debug === true) console.log(message);
	}
});

var ACLRoles = Backbone.Collection.extend({
	url : '/rest/aclroles',
	model : ACLRole,
	debug : false,
	sortAttribute : "role",
	initialize : function() {
		this.on('add', function(model) {
			this.log('ACLRoles::add::' + model.attributes.id);
		});
		this.on('remove', function(model) {
			this.log('ACLRoles::remove::' + model.attributes.id);
		});
		this.on('change', function(model) {
			this.log('ACLRoles::change::' + model.attributes.id);
		});
		this.on('sync', function(e) {
			this.log('ACLRoles::sync::' + e.length);
		});
	},
	comparator : function(model) {
		return model.get(this.sortAttribute);
	},
	log : function(message) {
		if (this.debug === true) console.log(message);
	}
});