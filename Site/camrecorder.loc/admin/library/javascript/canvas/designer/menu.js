/**
 * MenuScene
 */

(function(window, document, undefined){
	'use strict';

	var MenuScene = class MenuScene {
		constructor(canvas, options) {
			_.extend(this, Backbone.Events);

			this.workspace = {};
			this.canvas = canvas;
			this.options = _.defaults(I.pick(options || {}, MenuScene.validOptions), MenuScene.defaults);

			this.setupEnv();
			this.createScene().load();
		}; // constructor

		/**
		 * Setup
		 */
		setupEnv() {
			if (I(Logger).isObject()) {
				this.logger = Logger.get('MenuScene');

				var updateLogLevel = function handleOptionLogLevelChange(prop, val, oldval) {
					this.setLevel(val);
				}

				updateLogLevel = _.bind(updateLogLevel, this.logger);
				this.options.watch('logLevel', updateLogLevel);
			}
		} // setupEnv

		/**
		 * Scene Name
		 */
		static get sceneName() {
			return 'menu';
		}; // sceneName

		/**
		 * Options for create and contrstuctor
		 */
		static get validOptions() {
			return ['logLevel'];
		}; // validOptions

		/**
		 * Returns defaults with your optional options
		 */
		static get defaults() {
			return {
				logLevel: APP_ENV.logLevel
			};
		}; // defaults

		/**
		 * Options for create and contrstuctor
		 */
		static get events() {
			return ['create', 'load', 'unload'];
		}; // events

		/**
		 * Set a new value for log level
		 */
		setLogLevel() {
			return this._scene.loaded;
		}; // setLogLevel

		/**
		 * Is the scene loaded
		 */
		get loaded() {
			return this._scene.loaded;
		}; // loaded

		/**
		 * Is the scene loaded and current
		 */
		get active() {
			return this.loaded && this.canvas.scenes.current == MenuScene.sceneName;
		}; // active

		/**
		 * Load the scene
		 */
		load() {
			if (!this.active) {
				this.canvas.scenes.load(MenuScene.sceneName);
				this.trigger('load', this);
			}
			return this;
		}; // load

		/**
		 * Unload the scene
		 */
		unload() {
			if (this.loaded) {
				this.canvas.scenes.unload(MenuScene.sceneName);
				this.trigger('unload', this);
			}
			return this;
		}; // unload

		/**
		 * Create a new scene
		 */
		createScene() {
			if (typeof this._scene === 'undefined') {
				this._scene = this.canvas.scenes.create(MenuScene.sceneName, function() {});
				this.trigger('create', this);
			}
			return this;
		}; // createScene

		/**
		 * Returns all scene widgets
		 */
		get widgets() {
			return this._scene.objects;
		}; // widgets

		/**
		 * Function to perform on children
		 */
		forFachWidget(_callback) {
			I(this._scene.objects).each(_callback);
			return this;
		}; // forFachWidget

		/**
		 * Return widget by ID
		 */
		getWidgetById(id) {
			return this._scene.objects.find(function(widget) {
				if (widget.id == id)
					return widget;
			});
		}; // getWidgetById

		/**
		 * Create a widget from an object and add to scene
		 */
		addWidget(options) {
			var widget;
			switch (options.type) {
				case 'text':
					widget = this.canvas.display.text(options);
					break;

				case 'line':
					widget = this.canvas.display.line(options);
					widget.bind('mousemove', mbd.propertyEditor.handleMdWidgetMouseMove);
					break;

				case 'image':
					options.image = designer.clientPath(options.image);
					widget = this.canvas.display.image(options);
					break;

				default:
					break;
			}

			mbd.propertyEditor.dragAndDrop(widget);
			widget.bind('click tap', mbd.propertyEditor.handleMdWidgetClick);
			widget.bind('mouseenter', mbd.propertyEditor.handleMdWidgetMouseEnter);
			widget.bind('mouseleave', mbd.propertyEditor.handleMdWidgetMouseLeave);

			this._scene.add(widget);

			this.logger.debug('Added { id => ' + widget.id + ', title => ' + this.getWidgetTitle(widget) + ' }');
			this.trigger('widget-add', widget);
			
			designer.dirty = true;
			
			return this;
		}; // addWidget

		/**
		 * Returns a friendly title for widget
		 */
		getWidgetTitle(widget) {
			var title = widget.type.toTitleCase();
			if (widget.type == 'image')
				title += ': ' + widget.image;
			else if (widget.type == 'text')
				title += ': ' + widget.text;

			return title;
		}; // getWidgetTitle

		/**
		 * Removed the widget. Deleted!
		 */
		removeWidget(widget) {
			widget.remove();
			this.logger.info('Removed { id => ' + widget.id + ', title => ' + this.getWidgetTitle(widget) + ' }');
			designer.dirty = true;
			this.trigger('widget-remove', widget);
			return this;
		}; // removeWidget

		/**
		 * Returns widget as an object if (makeCopy = true) default: false, no
		 * identity properties returned
		 */
		getWidgetProperties(widget, makeCopy) {
			var isNew = I(makeCopy).isBoolean() ? makeCopy : false;

			var data = {};

			if (isNew === false) {
				data.id = widget.id;
				data.zIndex = widget.zIndex;
			}

			data.x = widget.x;
			data.y = widget.y;
			data.height = widget.height;
			data.width = widget.width;
			data.type = widget.type;
			data.rotation = widget.rotation;

			if (widget.type === 'text') {
				data.font = widget.font;
				data.text = widget.text;
				data.fill = widget.fill;
			}

			if (widget.type === 'line') {
				data.start = widget.start;
				data.end = widget.end;
				data.stroke = widget.stroke;
				data.cap = widget.cap;
			}

			if (widget.type === 'text' || widget.type === 'line')
				data.shadow = widget.shadow;

			if (widget.type === 'image')
				data.image = designer.fileName(widget.image);

			return data;
		}; // getWidgetProperties

		/**
		 * Returns a json string of the scene
		 */
		serializeScene() {
			var collection = [];

			for (var i = 0; i < this.widgets.length; i++) {
				var obj = this.widgets[i];

				if (!obj.pointerEvents)
					continue;

				collection[obj.zIndex] = this.getWidgetProperties(obj);
			}

			return JSON.stringify(collection);
		}; // serializeScene
	}

	window.Designer.MenuScene = MenuScene;
})(window, document);
