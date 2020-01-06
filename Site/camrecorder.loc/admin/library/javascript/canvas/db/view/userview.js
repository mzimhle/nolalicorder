/**
 * UserView
 */

var UserRow = Backbone.View.extend({
	tagName : 'tr',
	className : "animated fadeIn",
	debug : true,
	events : {
		"click button.glyphicon-pencil" : "editUser",
		"click button.glyphicon-trash" : "deleteUser"
	},
	initialize : function() {
		this.listenTo(this.model, "change", this.render);
		this.listenTo(this.model, "remove", this.remove);
	},
	constructor : function(attributes, options) {
		this.log('UserRow\'s constructor had been called');
		Backbone.View.apply(this, arguments);
	},
	render : function() {
		this.log('UserRow::render::' + this.model.get("id"));
		if (this.model.get("state") == "2") {
			this.remove();
			return this;
		}

		acl = aclroles.get(this.model.attributes.fk_aclroles);
		this.model.attributes.role = acl.get("role").toTitleCase();
		this.$el.html(this.template(this.model.attributes));
		$("tbody").append(this.$el);
		return this;
	},
	template : _.template(
`<td><%=role%></td>
<td><%=firstname%> <%=lastname%></td>
<td><%=email%></td>
<td>
	<div class="btn-group-sm pull-right" role="group" aria-label="Functions">
		<button data-toggle="tooltip" data-placement="left" title="Edit" data-id="<%=id%>" data-action="edit" type="button" class="btn btn-default glyphicon glyphicon-pencil listaction"></button>
		<button data-toggle="tooltip" data-placement="right" title="Delete" data-id="<%=id%>" data-action="delete" type="button" class="btn btn-default glyphicon glyphicon-trash listaction"></button>
	</div>
</td>`),
	editUser : function() {
		this.log("UserRow::Edit: " + this.model.get("id"));
	},
	deleteUser : function() {
		this.log("UserRow::Delete: " + this.model.get("id"));
		var usr = this.model;
		bootbox.confirm("Delete " + this.model.fullName() + ", Are you sure?", function(result) {
			if (result) {
				usr.save({
					state : "2"
				});
				usr.destroy();
			}
		});
	},
	log : function(message) {
		if (this.debug === true) console.log(message);
	}
});