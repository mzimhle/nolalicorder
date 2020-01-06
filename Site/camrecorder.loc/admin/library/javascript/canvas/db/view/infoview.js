/**
 * UserView
 */

var InfoView = Backbone.View
	.extend({
		tagName: 'div',
		className: "infoedit animated fadeIn",
		id: "infoedit",
		dirty: false,
		debug: false,
		events: {
			"click button.glyphicon-ok": "saveInfo",
			"click button.glyphicon-remove": "closeInfo",
			"blur input[data-required]": "inputChange"
		},
		render: function() {
			this.log('InfoView::render::' + this.model.get("id"));
			data = this.model.attributes;
			data.nameAlert = data.name == "" ? ' infoalert' : '';
			data.backgroundAlert = data.background == "" ? ' infoalert' : '';
			this.$el.html(this.template(data));
			console.log(this.$el.html(this.template(data)));
			this.updateBackgrounds();
			return this;
		},
		template: _
			.template(
				`<form>
	<div class="form-group">
		<label for="info-name"><span class="required glyphicon glyphicon-asterisk"></span>Name</label>
		<input data-required type="text" class="form-control<%=nameAlert%>" id="info-name" placeholder="Name" data-original="<%=name%>" value="<%=name%>">

		<label for="info-background"><span class="required glyphicon glyphicon-asterisk"></span>Background</label>
		<select data-required class="form-control<%=backgroundAlert%>" id="info-background" data-original="<%=background%>" value="<%=background%>" />

		<label for="info-created">Created</label>
		<input type="datetime" class="form-control" id="info-created" placeholder="1" data-original="<%=created%>" value="<%=created%>" readonly>

		<label for="info-modified">Modified</label>
		<input type="datetime" class="form-control" id="info-modified" placeholder="1" data-original="<%=modified%>" value="<%=modified%>" readonly>

		<div style="padding: 10px 50px;">
			<button type="button" class="pull-right btn btn-warning btninfo glyphicon glyphicon-remove"></button>
			<button type="button" class="btn btn-success btninfo glyphicon glyphicon-ok"></button>
		</div>
	</div>
</form>`
			),
		updateBackgrounds: function() {
			this.log('InfoView::updateBackgrounds::' + this.model.get("id"));
			$('#info-background').empty();

			I.getUrl("/image-gallery/files").then(function(response) {
				files = JSON.parse(response);
				if (files["files"]) {
					$.each(files["files"], function(key, imgFile) {
						if (imgFile["filename"].split('.').pop() === 'wmv') {
							$('#info-background').append($('<option>', {
								value: designer.fileName(imgFile["filelink"].replace('-snap.png', '.wmv'))
							}).text(imgFile["filename"].substring(0, imgFile["filename"].length - 4)));
						}
					});
					$('#info-background').append($('<option>', {
						value: '__background-white.wmv',
					}).text('White'));

					$("#info-background option[value='" + data.background + "']").attr('selected', 'selected');
				}
			}, function(error) {
				console.error("Failed!", error);
			});
		},
		saveInfo: function() {
			this.log("InfoView::Save: " + this.model.get("id"));

			var name = $('#info-name').val();
			//var length = $('#info-length').val();
			var background = $('#info-background').val();
			fk_clients = this.model.get('fk_clients');

			if (background) {
				backgroundImage = background.split('.').shift() + '-snap.png';
				if (designer.layout.background != backgroundImage) {
					designer.layout.background = backgroundImage;
					this.dirty = true;
				}
			}

			if (name === '') {
				designer.notice('Name needs a value!', 'danger');
				return this;
			}

			if (this.dirty) {
				this.model.set('name', name);
				//this.model.set('length', length);
				this.model.set('background', designer.layout.backgroundVideo);
				designer.dirty = true;
				this.dirty = false;
			}
			$('#edit-info').modal('hide');
			return this;
		},
		closeInfo: function() {
			this.log("InfoView::Close: " + this.model.get("id"));
			$('#edit-info').modal('hide');
			return this;
		},
		inputChange: function(e) {
			this.log('InfoView::input::change');

			obj = {};
			obj.id = e.target.id;
			obj.val = $(e.target).val();

			if (obj.val == '') {
				obj.required = 'alert';
				$('input#' + obj.id).addClass('infoalert');
			} else {
				obj.required = 'off';
				$('input#' + obj.id).removeClass('infoalert');
			}
			this.dirty = true;
			return this;
		},
		log: function(message, forceDebug) {
			var lDebug = this.debug;
			if (typeof forceDebug == 'boolean')
				lDebug = forceDebug;
			if (lDebug === true) console.log(message);
		}
	});
