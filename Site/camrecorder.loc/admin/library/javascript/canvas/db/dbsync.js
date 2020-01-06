/**
 * DBSync
 * @author philip<peep@cathedral.co.za>
 * @version 3
 */

var DBSync = {
		collection: [],
		staticCollection: {},
		interval: 10000,
		timer: null,
		debug: false,
		start : function() {
			DBSync.log('DBSync::start');
			DBSync.timer = setInterval(function() {
				for (var i in DBSync.collection) {
					DBSync.log('DBSync::sync::'+i);
					DBSync.collection[i].fetch();
				}
			}, DBSync.interval);
		},
		stop : function() {
			DBSync.log('DBSync::stop');
			clearInterval(DBSync.timer);
		},
		createModel : function(modelName, id, url, attributeDefaults) {
			return Backbone.Model.extend({
				name : modelName,
				urlRoot : url,
				idAttribute: id,
				debug : true,
				defaults : attributeDefaults,
				initialize : function() {
					this.on("add", function(model) {
						this.log(this.name + '::add::'+model.id);
					});
					this.on("change", function(model, state) {
						this.log(this.name + '::change::'+model.id);
					});
					this.on("remove", function(model, state) {
						this.log(this.name + '::remove::'+model.id);
					});
					this.on("sync", function(model, collection, response) {
						this.log(this.name + '::sync::' + model.id);
					});
				},
				log : function(message) {
					if (this.debug === true)
						console.log(message);
				}
			});
		},
		createCollection : function(collectionName, url, modelObj) {
			return Backbone.Collection.extend({
				name : collectionName,
				url : url,
				model : modelObj,
				debug : false,
				sortAttribute: modelObj.idAttribute,
				initialize : function() {
					this.on('add', function(model) {
						this.log(this.name + '::add::'+model.id);
					});
					this.on('remove', function(model) {
						this.log(this.name + '::remove::'+model.id);
					});
					this.on('change', function(model) {
						this.log(this.name + '::change::'+model.id);
					});
					this.on('sync', function(e) {
						this.log(this.name + '::sync::' + e.length);
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
		},
		log : function(message) {
			if (this.debug === true)
				console.log(message);
		}
};
