/**
 * cssFonts
 */
var StatusView = Backbone.View.extend({
	tagName: 'tr',
	className: "status-item",
	logger: null,
	groupIds: [],
	events: {
		
	},
	setGroupIds: function(ids) {
		if (typeof ids == 'undefined')
			ids = [];
		
		if (!I(this.groupIds).isEqual(ids)){
			this.groupIds = ids;
			logger.debug('ids: ', this.groupIds);
			this.render();
		}
	},
	render: function() {
		logger.debug('StatusView:Render:', this.collection);
		this.$el.empty();
		for (i=0;i<this.collection.models.length;i++) {
			var model = this.collection.models[i];
			var data = model.attributes;
			data['description'] = this.statusLookup[data['menustate']];
			
			try {
				data['group'] = DBSync.staticCollection.groups.findWhere({id: data['fk_groups']}).get('name');
			} catch (e) {
				data['group'] = '';
			}
			
			data['totalprogress'] = (((data['filesdone']-1)*100)+(data['progress']*1))/data['filestotal'];
			
			if(data['totalprogress'] < 0)
				data['totalprogress'] = 0;
			
			data['totalprogress'] = Math.round(data['totalprogress']*10)/10;
			
			if (this.groupIds.length == 0 || this.groupIds.indexOf(data['fk_groups']) >= 0) {
				var eltr = this.template(data);
				this.$el.append(eltr);
				if (model.hasOwnProperty('progress'))
					document.getElementById(data[id]).classList.toggle('pulse');
			}
		}
		
		return this;
	},
	statusLookup: {
		outdated: 'Menus are outdated',
		current: 'Menus are up to date'
	},
	template: _.template(
		`
<tr id="<%=id%>" class="animated">
	<td><%=version%></td>
	<td><%=store%></td>
	<td><%=group%></td>
	<td><%=progress%>%</td>
	<td><%=totalprogress%>%</td>
	<td><%=filesdone%> / <%=filestotal%></td>
	<td class="text-uppercase"><abbr title="<%=description%>" class="<%=menustate%>"><%=menustate%></abbr></td>
	<td><%=updatedone%></td>
	<td><%=lastseen%></td>
</tr>
	`
	)
});
