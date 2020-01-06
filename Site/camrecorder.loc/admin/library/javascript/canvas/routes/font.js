/**
 * Route: font
 */

JSLoader.spinnerStart().loadModules(['cssfontview', 'fileinput']);

JSLoader.once('done', function() {
	var $fi = $("#input-fontfile").fileinput({
		'showPreview': false,
		'maxFileSize': 1000,
		'allowedFileExtensions': ['ttf', 'otf'],
		'elErrorContainer': '#errorBlock'
	});

	$fi.on('filebatchuploadcomplete', function(event, files, extra) {
		$('.font-list').html('');
		for (var int = 0; int < APP_ENV.data.font.length; int++) {
			var cfv = new cssFontView({
				model: new cssFont(APP_ENV.data.font[int])
			});
			cfv.render();

			$('.font-list').append(cfv.$el);
			JSLoader.addScripts([APP_ENV.data.font[int].cssFile]);
		}

		APP_ENV.data.font = [];

		JSLoader.start();
		$fi.fileinput('clear');
		$fi.fileinput('refresh');
		$fi.fileinput('enable');

		$('[data-toggle="tooltip"]').tooltip('destroy');
		$('[data-toggle="tooltip"]').tooltip();
	});

	$fi.on('fileuploaded', function(event, data, previewId, index) {
		var form = data.form,
			files = data.files,
			extra = data.extra,
			response = data.response,
			reader = data.reader;
		// Clear current fonts (yes should use collection)

		APP_ENV.data.font = response.data;
	});
});

(function() {
	$('button.close').on('click', function(e) {
		var fontPanel = this.parentNode.parentNode;
		var family = this.getAttribute('data-family');

		bootbox.confirm("Delete " + family + ", Are you sure?", function(result) {
			if (result) {
				I.getUrl(APP_ENV.data.route.ul + family, true).then(function(response) {
					fontPanel.remove();
				}, function(error) {
					console.error("Failed!", error);
				});
			}
		});
	});

	$('[data-toggle="tooltip"]').tooltip('destroy');
	$('[data-toggle="tooltip"]').tooltip();
})();
