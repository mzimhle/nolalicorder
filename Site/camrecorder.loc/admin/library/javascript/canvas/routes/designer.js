/**
 * Author : Philip
 * Route : designer
 */

JSLoader.loadModules(['menus', 'bootbox'], 5);

(function() {
	I(document.getElementsByClassName('button-delete')).each(function(v) {
		v.addEventListener('click', function() {
			item = this;
			bootbox.confirm('Delete ' + $(item).data('name') + ', Are you sure?', function(result) {
				if (result) {
					menu = new Menu({
						id: $(item).data('id')
					});
					menu.once('sync', function(model) {
						model.destroy({
							success: function(model, response) {
								document.getElementById('menu-' + model.attributes.id).remove();
							}
						});
					});
					menu.fetch();
				}
			});
		}, false);
	});
	
	I(document.getElementsByClassName('button-duplicate')).each(function(v) {
		v.addEventListener('click', function() {
			item = this;
			bootbox.confirm('Duplicate ' + $(item).data('name') + ', Are you sure?', function(result) {
				if (result) {
					menu = new Menu({
						id: $(item).data('id')
					});
					menu.once('sync', function(model) {
						model.attributes.id = null;
						model.attributes.name += " Copy";
						model.save();
						var id = model.get('id');
						window.location.href = APP_ENV.route;
					});
					menu.fetch();
				}
			});
		}, false);
	});
})();

JSLoader.ready(function() {
	$('[data-toggle="tooltip"]').tooltip('destroy');
	$('[data-toggle="tooltip"]').tooltip();
});
