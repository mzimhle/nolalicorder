/**
 * cssFonts
 */
var cssFontView = Backbone.View.extend({
	tagName: 'div',
	className: "col-md-3",
	events: {
		"click .close": "deleteFont"
	},
	render: function() {
		this.$el.html(this.template(this.model.attributes));
		return this;
	},
	template: _.template(
		`
<div class="panel panel-default">
	<button data-family="<%=family%>" type="button" class="close" aria-label="Close"><span title="Delete <%=family%>?" data-toggle="tooltip" data-placement="top" class="delete" aria-hidden="true">×</span></button>
    <div class="panel-heading">
    	<h4 class="<%=cssClass%>"><%=family%></h4>
    </div>
    <ul class="list-group <%=cssClass%>">
		<li class="list-group-item">Regular</li>
		<li class="list-group-item" style="font-weight: bold; font-style: italic;">Bold Italic</li>
		<li class="list-group-item" style="font-weight: bold;">Bold</li>
		<li class="list-group-item" style="font-style: italic;">Italic</li>
		<li class="list-group-item" style="font-style: oblique;">Oblique</li>
	</ul>
</div>
	`
	),
	deleteFont: function() {
		var fontPanel = this;
		var family = this.model.get('family');

		bootbox.confirm("Delete " + family + ", Are you sure?", function(result) {
			if (result) {
				I.getUrl('/font/ul/' + family, true).then(function(response) {
					fontPanel.remove();
				}, function(error) {
					console.error("Failed!", error);
				});
			}
		});
	}
});
