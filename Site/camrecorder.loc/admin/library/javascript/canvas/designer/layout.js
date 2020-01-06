/**
 *
 */

(function(window, document, undefined){
	'use strict';

	var Layout = class Layout {
		constructor(options) {
			_.extend(this, Backbone.Events);

			this.canvas = oCanvas.create(Layout.defaults.canvas);
		};

		static get defaults() {
			return {
				canvas: {
					canvas: '#canvas',
					background: 'transparent'
				},
				font: {
					font: 'normal normal normal 32px Sans-Serif',
					colour: '#000'
				}
			};
		};

		get el() {
			return this.canvas.canvasElement;
		};

		get background() {
			var backgroundImage = designer.layout.canvas.canvasElement.style.backgroundImage;
			if (backgroundImage != '')
				backgroundImage = backgroundImage.match(/\/(clients|designer).*\.png/)[0].split('/').pop();
			return backgroundImage
		};

		set background(background) {
			if (background.indexOf('.wmv') >= 0)
				this.backgroundVideo = background;
			else
				designer.layout.canvas.canvasElement.style.backgroundImage = "url('" + designer.clientPath(background) + "')";
		};

		get backgroundVideo() {
			return this.background.replace('-snap.png', '.wmv');
		}

		set backgroundVideo(backgroundVideo) {
			this.background = backgroundVideo.split('.').shift() + '-snap.png';
			return this;
		}
	}

	window.Designer.Layout = Layout;

})(window, document);
