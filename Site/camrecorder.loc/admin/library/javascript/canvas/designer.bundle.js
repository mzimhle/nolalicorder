/**
 * MenuScene
 * 
 * To create designer.bundle.js
 * 
 * cat designer.js layout.js layers.js menu.js grid.js > ../designer.bundle.js
 */

(function(window, document, undefined){
    'use strict';

    var Designer = class Designer {
        constructor() {
            _.extend(this, Backbone.Events);

            this.layout = new Designer.Layout();
            this.menuscene = new Designer.MenuScene(this.layout.canvas);
            this.gridscene = new Designer.GridScene(this.layout.canvas);
            this.layereditor = new Designer.LayerEditor(this.menuscene);
            
            this.dirty = false;
            
            this.setupEvents();
        };

        static get Layout() {
            return !this._Layout ? undefined : this._Layout;
        };
        static set Layout(layout) {
            this._Layout = layout;
        };

        get layout() {
            return !this._layout ? undefined : this._layout;
        };
        set layout(layout) {
            this._layout = layout;
        };

        static get MenuScene() {
            return !this._MenuScene ? undefined : this._MenuScene;
        };
        static set MenuScene(menuscene) {
            this._MenuScene = menuscene;
        };
        get menuscene() {
            return !this._menuscene ? undefined : this._menuscene;
        };
        set menuscene(menuscene) {
            this._menuscene = menuscene;
        };

        static get GridScene() {
            return !this._GridScene ? undefined : this._GridScene;
        };
        static set GridScene(gridscene) {
            this._GridScene = gridscene;
        };
        get gridscene() {
            return !this._gridscene ? undefined : this._gridscene;
        };
        set gridscene(gridscene) {
            this._gridscene = gridscene;
        };

        static get LayerEditor() {
            return !this._LayerEditor ? undefined : this._LayerEditor;
        };
        static set LayerEditor(layereditor) {
            this._LayerEditor = layereditor;
        };

        get layereditor() {
            return !this._layereditor ? undefined : this._layereditor;
        };
        set layereditor(layereditor) {
            this._layereditor = layereditor;
        };

        get dirty() {
            return this._dirty;
        };
        
        set dirty(dirty) {
            if (this._dirty != dirty) {
                this._dirty = dirty;
                this.trigger('dirty', {
                    event: 'dirty',
                    dirty: dirty
                });
            }
        };
        
        setupEvents() {
        	this.gridscene.on('new', function(snapTo, grid) {
            	this.dirty = true;
        	}, this);
        };
        
        getPath(clientFile) {
            if (clientFile) {
                return globalPath+'/media/clients/' + APP_ENV.client.fk_clients + '/';
            }
            return globalPath+'/images/designer/';
        };
        
        clientPath(withFile) {
            withFile = withFile || '';
            var clientFile = true;
            if (this.fileName(withFile).substring(0,2) == '__') {
                clientFile = false;
            }
            
            return this.getPath(clientFile) + this.fileName(withFile);
        };
        
        fileName(fileWithPath) {
            return fileWithPath.split('/').pop();
        };

        get canvas() {
            return !this.layout ? undefined : this.layout.canvas;
        }; // canvas

        /**
		 * Send a Message to user
		 * 
		 * Types: default, primary, success, warning, info, danger
		 */
        notice(message, type) {
            return $.notify({
                message: message
            }, {
                type: type,
                z_index: 1060
            });
        }; // notice
    }

    window.Designer = Designer;
})(window, document);
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
					background: 'blue'
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
/**
 * Layers Editor
 */
(function(window, document, undefined){
    'use strict';

    var LayerEditor = class LayerEditor {
        constructor(menuscene, options) {
            _.extend(this, Backbone.Events);

            this._menuscene = menuscene;

            this.el.addEventListener('dragover', LayerEditor.handleDragOver, false);
            this.el.addEventListener('drop', LayerEditor.handleDrop, false);
            this.el.addEventListener('click', LayerEditor.handleClick, false);
        } // constructor

        get el() {
            return document.getElementById('layers');
        }; // el

        /**
         * update widget z-index after layer drag & drop
         */
        updateLayerWidgets() {
            mbd.propertyEditor.propertyBarDestroy();

            $('#' + designer.layereditor.el.id + ' div').each(function (i, v) {
                document.getElementById(v.id).setAttribute('data-layer', i);

                var itemId = v.id.replace('tl', '');
                var property = designer.menuscene.getWidgetById(itemId);
                property.zIndex = i;
                property.redraw();
            });
            designer.dirty = true;
        } // updateLayerWidgets

        static handleClick(e) {
            var itemId = e.target.id.replace('tl', '');
            var property = designer.menuscene.getWidgetById(itemId);
            mbd.propertyEditor.handleMdWidgetClick.call(property);
        } // handleClick

        static handleDragStart(e) {
            // none, copy, copyLink, copyMove, link, linkMove, move,
            // all, uninitialized
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData("text", e.target.id);
            this.style.opacity = '0.4';
        } // handleDragStart

        static handleDragOver(e) {
            e.preventDefault();
            // none, copy, link, move
            e.dataTransfer.dropEffect = 'move';
            return false;
        } // handleDragOver

        static handleDrop(e) {
            e.stopPropagation();

            var tl = document.getElementById(e.dataTransfer.getData("text"));
            tl.style.opacity = '1';

            var $target = null;
            // Check if we dropping on the header row
            if (e.srcElement.tagName == 'DIV') {
                // then just simple append the row
                $target = $(e.srcElement);
                // $(e.srcElement).after(tl);
            } else {
                // else place it after the row we over
                $target = $(e.srcElement.parentElement);
                // $(e.srcElement.parentElement).after(tl);
            }

            if (tl.getAttribute('data-layer') > $target.data('layer')) {
                $target.before(tl);
            } else {
                $target.after(tl);
            }

            designer.layereditor.updateLayerWidgets();
            return false;
        } // handleDrop

        createCloneButton() {
            // Clone button (that no longer clones)
            var buttonClone = document.createElement('i');
            buttonClone.classList.add('btn', 'fa', 'fa-clone', 'pull-right', 'hide');
            buttonClone.setAttribute('title', 'Clone');
            buttonClone.textContent = ' Duplicate';
            // Cloning ran into problems with events
            buttonClone.addEventListener('click', function () {
                // So now data is a obj witch copies the
                // primary properties from widget
                var data = designer.menuscene.getWidgetProperties(mbd.property, true);

                // Changes the position a wee bit
                data.x += 50;
                data.y += 50;

                designer.menuscene.addWidget(data);
                designer.layereditor.layersUpdate();
            });

            return buttonClone;
        } // createCloneButton

        /**
         * Creates the timeline layers
         */
        layersUpdate() {
            var layers = [];
            var currentGrid = designer.gridscene.hideLines();

            designer.menuscene.forFachWidget(function (v, i) {
                // Timeline Title
                var title = document.createElement('span');
                title.classList.add('pull-left', 'title');
                // Base layer title
                title.textContent = v.type.toTitleCase();

                // Widget types extended layer titles
                if (v.type == 'text') { // Text
                    title.textContent += ': ' + v.text.substring(0, 20) + (v.text.length > 20 ? '...' : '');
                } else if (v.type == 'image') { // Image
                    title.textContent += ': ' + v.image.split('/').pop().split('.').shift()
                }
                
                // Clone button (that no longer clones)
                var btnClone = designer.layereditor.createCloneButton();

                // Timeline (layer only in v1)
                var tl = document.createElement('div');
                tl.id = 'tl' + v.id.toString();
                tl.setAttribute('draggable', true);
                tl.setAttribute('data-layer', v.zIndex);
                tl.classList.add('layers');

                // Add elements to #timeline.layer
                tl.appendChild(title);
                tl.appendChild(btnClone);

                tl.addEventListener('dragstart', LayerEditor.handleDragStart, false);
                layers[v.zIndex - designer.gridscene.lines] = tl;
            });

            if (currentGrid)
                designer.gridscene.showLines();

            // Cleaning the timeline to make space, for a full
            // refresh
            designer.layereditor.el.innerHTML = '';
            _(layers).each(function (v) {
                designer.layereditor.el.appendChild(v);
            });

            // Set the active layer to the active widget if any
            if (mbd.property !== null) {
                if (mbd.property.children.length > 0) {
                    if (document.getElementById('lt' + mbd.property.id) !== null) {
                        document.getElementById('tl' + mbd.property.id).classList.add('active');
                        $(document.getElementById('tl' + mbd.property.id)).children('i').removeClass('hide');
                    }
                }
            }

            return this;
        } // layersUpdate
    }

    window.Designer.LayerEditor = LayerEditor;
})(window, document);
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
/**
 * GridScene
 * 
 * @author Philip Michael Raab <philip@gaap.co.za>
 * @version 0.5.0.100
 */
(function(window, document, undefined){
    'use strict';

    /**
     * Creates a new GridScene.
     * @class
     */
    var GridScene = class GridScene {
        /**
         * @constructs
         * @param {oCanvas} canvas
         * @param {Object} options
         */
        constructor(canvas, options) {
            _.extend(this, Backbone.Events);
            this.name = 'grid';

            this.defaults = {
                snapTo: 20,
                width: 1,
                colour: '#D4D4D4',
                snap: true
            };

            this.current = {
                snapTo: 0,
                width: 0,
                colour: ''
            };
            
            // Throttle this so not every step sent to server, so when jumping from 20-80 = 1 call not 60.  
            this.createGrid = _.throttle(this._createGrid, 3000, options);

            this.canvas = canvas;
            options = _.pick(options, this.validOptions);
            _.defaults(options, this.defaults);
            this.createGrid(options);
            this._snap = true;
        }

        /**
         * Options for create and contrstuctor
         */
        get events() {
            return ['empty', 'new', 'load', 'unload'];
        }

        /**
         * Options for create and contrstuctor
         */
        get validOptions() {
            return ['snapTo', 'width', 'colour', 'snap'];
        }

        /**
         * Get number of active grid lines
         */
        get lines() {
            if (this._scene === undefined)
                return 0;
            return this.visible ? this._scene.objects.length : 0;
        }

        /**
         * Get snap enabled
         * 
         * @return {Boolean}
         */
        get snap() {
            return this.current.snap;
        }

        /**
         * Set snap enabled
         * 
         * @param {Boolean} snap - Snap state
         * @return {GridScene}
         */
        set snap(snap) {
            if (snap === true)
                this.current.snap = true;
            else
                this.current.snap = false;

            return this;
        }

        /**
         * Get current snapTo
         */
        get snapTo() {
            return this.current.snapTo;
        }

        /**
         * Is the grid Visible & in use
         */
        get visible() {
            return this._scene.loaded;
        }

        /**
         * Set the lines visibility
         * 
         * return GridScene
         */
        set visible(visible) {
            if (visible === true)
                this.showLines();
            else
                this.hideLines();

            return this;
        }

        /**
         * Grid State
         */
        get state() {
            return {
                visible: this._scene.loaded,
                lines: this.lines
            };
        }

        /**
         * Load the grid scene to show lines
         */
        showLines() {
            if (!this.visible) {
                this.canvas.scenes.load(this.name);
                this.trigger('load', this.current, this.state);
                return true;
            }
            return false;
        }

        /**
         * Unload the grid scene to hide lines
         */
        hideLines() {
            if (this.visible) {
                this.canvas.scenes.unload(this.name);
                this.trigger('unload', this.current, this.state);
                return true;
            }
            return false;
        }

        /**
         * What is the next point on a axis where a grid line will be found
         */
        nextGridLine(currentValue) {
            var gridValue = currentValue || 0;
            return (Math.floor(gridValue / this.snapTo) + 1) * this.snapTo;
        }

        /**
         * Create a new grid if options have changed
         * None throttled version
         * 
         * Called with throttle createGrid
         */
        _createGrid(options) {
            var state = false;
            if (this._scene !== undefined)
                state = this.hideLines();

            options = I.pick(options, this.validOptions);
            _.defaults(options, this.current);

            if (!I.isEqual(options, this.current)) {
                this.emptyGrid(options);
                if (this.addLines(options)) {
                    this.current = options;
                    this.trigger('new', options, this.state);
                    if (state) this.showLines();
                }
            }
            return this;
        }

        /**
         * Create a new empty grid
         */
        emptyGrid(options) {
            this._scene = this.canvas.scenes.create(this.name, function() {});
            this.trigger('empty', options, this.state);
            return true;
        }

        /**
         * Add grid lines to an empty grid
         */
        addLines(options) {
            if (this._scene.objects.length > 0)
                return false;

            this.hideLines();
            this.addLinesToAxis('x', options);
            this.addLinesToAxis('y', options);
            return true;
        }

        /**
         * Add lines to an axis
         */
        addLinesToAxis(axis, options) {
            var i = options.snapTo;
            var sx, sy, ex, ey, max;

            if (axis == 'x') {
                sx = ex = i;
                sy = 0;
                ey = this.canvas.height;
                max = this.canvas.width;
            } else if (axis == 'y') {
                sx = 0;
                ex = this.canvas.width;
                sy = ey = i;
                max = this.canvas.height;
            }

            while (i < max) {
                var line = this.canvas.display.line({
                    start: {
                        x: sx,
                        y: sy
                    },
                    end: {
                        x: ex,
                        y: ey
                    },
                    stroke: options.width + "px " + options.colour,
                    cap: "butt",
                    pointerEvents: false
                });
                this._scene.add(line);
                i += options.snapTo;
                if (axis == 'x') {
                    sx = ex = i;
                } else if (axis == 'y') {
                    sy = ey = i;
                }
            }
            return true;
        }
    }

    window.Designer.GridScene = GridScene;
})(window, document);
