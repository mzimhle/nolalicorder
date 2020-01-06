/**
 * Groups
 */

var Group = Backbone.Model.extend({
	urlRoot : '/rest/groups',
	idAttribute: "id",
	debug : false,
	defaults : {
		"id" : null,
		"fk_clients" : null,
		"name" : null,
		"fk_groups" : null
	},
	initialize : function() {
		this.on("add", function(model) {
			this.log('Group::add::'+model.attributes.id);
		});
		this.on("change", function(model, state) {
			this.log('Group::change::'+model.attributes.id);
		});
		this.on("remove", function(model, state) {
			this.log('Group::remove::'+model.attributes.id);
		});
		this.on("sync", function(model, collection, response) {
			this.log('Group::sync::' + model.attributes.id);
		});
	},
	log : function(message) {
		if (this.debug === true)
			console.log(message);
	}
});

var Groups = Backbone.Collection.extend({
	url : '/rest/groups',
	model : Group,
	debug : false,
	sortAttribute: "name",
	initialize : function() {
		this.on('add', function(model) {
			this.log('Groups::add::'+model.attributes.id);
		});
		this.on('remove', function(model) {
			this.log('Groups::remove::'+model.attributes.id);
		});
		this.on('change', function(model) {
			this.log('Groups::change::'+model.attributes.id);
		});
		this.on('sync', function(e) {
			this.log('Groups::sync::' + e.length);
		});
	},
	comparator: function (model) {
        return model.get(this.sortAttribute);
    },
	log : function(message) {
		if (this.debug === true)
			console.log(message);
	}
});
