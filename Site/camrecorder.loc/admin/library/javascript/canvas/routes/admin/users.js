/**
 * Author : Philip Route : admin/users
 */

JSLoader.spinnerStart().loadModules([ 'aclroles', 'groups', 'userview', 'dbsync'], 10);

$(document).ready(function() {
	var status = {
		aclroles : false,
		groups : false,
		users : false
	};

	_.extend(status, Backbone.Events);

	aclroles = new ACLRoles();
	groups = new Groups();
	users = new Users();
	usersgroup = new Users();

	function init() {
		if (status.aclroles && status.groups && status.users) {
			$('tbody').empty();
			$('thead').toggleClass('hidden animated fadeIn');
			$('label.group').click(function() {
				$('label[data-selected="true"]').attr('data-selected', 'false');
				$(this).attr("data-selected", "true");
				var groupid = $('label[data-selected="true"]').attr('data-id').toString();

				usersgroup.set(users.filter({
					fk_groups : groupid
				}));
			});
		}
	}

	status.listenToOnce(aclroles, 'sync', function() {
		status.aclroles = true;
		init();
	});

	status.listenToOnce(groups, 'sync', function() {
		status.groups = true;
		init();
	});

	status.listenToOnce(users, 'sync', function() {
		status.users = true;
		init();
	});

	groups.fetch();
	users.fetch();
	aclroles.fetch();

	// DBSync.debug = true;
	DBSync.collection.push(users);
	// DBSync.collection.push(aclroles);
	DBSync.start();

	usersgroup.on("add", function(amodel) {
		uv = new UserRow({
			model : amodel
		});
		uv.render();
	});

	users.on("add", function(amodel) {
		if (($('label[data-selected="true"]').length > 0) && ($('label[data-selected="true"]').attr("data-id").toString() == amodel.get("fk_groups")))
			usersgroup.add(amodel);
	});
});
