/**
 * Users
 */

var User = Backbone.Model.extend({
	urlRoot : '/rest/users',
	idAttribute : "id",
	debug : false,
	defaults : {
		"id" : null,
		"fk_clients" : null,
		"fk_aclroles" : null,
		"fk_groups" : null,
		"email" : null,
		"password" : null,
		"firstname" : null,
		"lastname" : null,
		"state" : null,
		"modified" : null
	},
	fullName : function() {
		return [this.attributes.firstname, this.attributes.lastname].join(' ');
	},
	initialize : function() {
		this.on("add", function(model) {
			this.log('User::add::' + model.attributes.id);
		});
		this.on("change", function(model, state) {
			this.log('User::change::' + model.attributes.id);
		});
		this.on("remove", function(model, state) {
			this.log('User::remove::' + model.attributes.id);
		});
		this.on("sync", function(model, collection, response) {
			this.log('User::sync::' + model.attributes.id);
		});
	},
	log : function(message) {
		if (this.debug === true) console.log(message);
	}
/*
 * render : function() { console.log('User::render'); acl =
 * sdata.aclroles.filter({ id : this.attributes.fk_aclroles })[0];
 * 
 * data = this.attributes; data.role = acl.get("role").toTitleCase();
 * 
 * var li = userListItem(data); $(".block_list").append(li).hide().slideDown(); }
 */
});

var Users = Backbone.Collection.extend({
	url : '/rest/users',
	model : User,
	debug : false,
	sortAttribute : "firstname",
	initialize : function() {
		this.on('add', function(model) {
			this.log('Users::add::' + model.attributes.id);
		});
		this.on('remove', function(model) {
			this.log('Users::remove::' + model.attributes.id);
		});
		this.on('change', function(model) {
			this.log('Users::change::' + model.attributes.id);
		});
		this.on('sync', function(e) {
			this.log('Users::sync::' + e.length);
		});
	},
	comparator : function(model) {
		return model.get(this.sortAttribute);
	},
	log : function(message) {
		if (this.debug === true) console.log(message);
	}
});
