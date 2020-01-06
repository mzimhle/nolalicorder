/**
 * route: bundle
 */

JSLoader.spinnerStart().loadModules(['notify', 'bundles']);

var bundler;
JSLoader.ready(function() {
	logger = Logger.get('Bundle');

	logger.setLevel(Logger.getDEBUG());

	var icons = {
		menu: '/img/icons/menu25.png'
	}

	function preload(sources) {
		var images = [];
		for (i = 0; i < sources.length; i++) {
			images[i] = (new Image()).src = sources[i];
		}
	}
	preload(_(icons).values());

	var screens = document.getElementById('screens');
	var btnAddScreen = document.getElementById('btnAddScreen');
	var statusMessage = document.getElementById('status-panel');
	var btnNew = document.getElementById('btnNewBundle');
	var btnBuild = document.getElementById('btnBuildBundle');
	var iconBuilding = document.getElementById('spinnerBuilding');

	$('[data-toggle="tooltip"]').tooltip();

	class Bundler {
		constructor() {
			this.status = Bundler.statusOptions.EMPTY;
			this._listeners = {};
		};

		static get template() {
			return {
				"format": "1.0.0",
				"version": null,
				"assets": [],
				"menus": [],
				"screens": []
			};
		};

		static get statusOptions() {
			return {
				EMPTY: 'empty',
				NEW: 'new',
				READY: 'ready',
				BUNDLE: 'bundle',
				ERROR: 'error'
			};
		};

		static get statusArray() {
			return ['empty', 'new', 'ready', 'bundle'];
		};

		static get configurationErrors() {
			return this._configurationErrors || [];
		};

		static set configurationErrors(configurationErrors) {
			this._configurationErrors = configurationErrors;
		};

		/**
		 * Events
		 */
		on(type, listener) {
			if (typeof this._listeners[type] == "undefined") {
				this._listeners[type] = [];
			}

			this._listeners[type].push(listener);
		};

		trigger(event) {
			if (typeof event == "string") {
				event = {
					type: event
				};
			}
			if (!event.target) {
				event.target = this;
			}

			if (!event.type) { //falsy
				throw new Error("Event object missing 'type' property.");
			}

			if (typeof this._listeners == 'undefined')
				this._listeners = {};

			if (this._listeners[event.type] instanceof Array) {
				var listeners = this._listeners[event.type];
				for (var i = 0, len = listeners.length; i < len; i++) {
					listeners[i].call(this, event);
				}
			}
		};

		off(type, listener) {
			if (this._listeners[type] instanceof Array) {
				var listeners = this._listeners[type];
				for (var i = 0, len = listeners.length; i < len; i++) {
					if (listeners[i] === listener) {
						listeners.splice(i, 1);
						break;
					}
				}
			}
		};

		/**
		 * Bundler
		 */
		get bundle() {
			if (typeof this._bundle == 'undefined') {
				this._bundle = Bundler.template;
			}
			return this._bundle;
		};

		get status() {
			if (this.bundle.screens.length == 0) {
				this.status = Bundler.statusOptions.EMPTY;
			} else if (this.menuCount == 0) {
				this.status = Bundler.statusOptions.NEW;
			} else if (this._status != Bundler.statusOptions.BUNDLE && this._status != Bundler.statusOptions.ERROR) {
				this.status = Bundler.statusOptions.READY;
			}
			return this._status;
		};

		set status(status) {
			if (Bundler.statusArray.indexOf(status) >= 0) {
				if (this._status != status) {
					this._status = status;
					this.trigger({
						'type': 'status',
						'message': this.statusMessage,
						'data': status
					});
				}
			}

			return this;
		};

		get statusMessage() {
			var msg;
			switch (this.status) {
				case Bundler.statusOptions.EMPTY:
					msg = 'Click the [+] Add Screen icon above to start building your bundle.';
					break;

				case Bundler.statusOptions.NEW:
					msg = 'Now drag some menus from the left to your screens.';
					break;

				case Bundler.statusOptions.READY:
					msg = 'Happy with your bundle? Click the [Save] Icon above to publish it.';
					break;

				case Bundler.statusOptions.BUNDLE:
					msg = 'Menu Bundle published.';
					break;

				case Bundler.statusOptions.ERROR:
					msg = 'There was an error building your bundle.';
					break;

				default:
					break;
			}
			return msg;
		};

		get menuCount() {
			var count = 0;
			for (var i = 0; i < this.bundle.screens.length; i++) {
				count += this.bundle.screens[i].menus.length;
			}
			return count;
		};

		buildBundleConfiguration() {
			var bundleMenus = [];
			this.messages = [];
			for (var i = 0; i < this.bundle.screens.length; i++) {
				bundleMenus[i] = APP_ENV.data.menus.filter(function(m) {
					return bundler.bundle.screens[i].menus.indexOf(Number.parseInt(m.id)) >= 0;
				});
			}
			this.bundle.menus = _.flatten(bundleMenus);
			this.bundle.assets = this.extractAssets();

			this._configuration = JSON.stringify(this.bundle);

			var nextState = Bundler.statusOptions.BUNDLE;

			if (Bundler.configurationErrors.length > 0) {
				logger.info('Bundle Errors: ', Bundler.configurationErrors);
				nextState = Bundler.statusOptions.ERROR;
				this.messages = Bundler.configurationErrors;
			}

			this.status = nextState;

			return this;
		};

		extractAssets() {
			var bundleAssets = [];
			var bundleErrors = [];
			I(this.bundle.menus).each(function(v, i) {
				if (I(_.pluck(bundleAssets, v.background.split('/').pop())).without(undefined).length == 0) {
					var fileAsset = APP_ENV.data.files[v.background.split('/').pop()];
					if (fileAsset)
						bundleAssets.push(fileAsset);
					else
						bundleErrors.push(v.background);
				}

				I(v.itemdata).each(function(d, idx) {
					switch (d.type) {
						case 'image':
							if (I(_.pluck(bundleAssets, d.image.split('/').pop())).without(undefined).length == 0) {
								var fileAsset = APP_ENV.data.files[d.image.split('/').pop()];
								if (fileAsset)
									bundleAssets.push(fileAsset);
								else
									bundleErrors.push(d.image);
							}
							break;

						case 'text':
							var font = d.font.split('px/1').pop().trim();
							var fontAssets = APP_ENV.data.fonts.data[font];
							if (fontAssets) {
								for (i = 0; i < fontAssets.length; i++) {
									if (I(_.pluck(bundleAssets, fontAssets[i].filename)).without(undefined).length == 0) {
										var addAsset = true;
										for (var a = 0; a < bundleAssets.length; a++) {
											if (bundleAssets[a].filename == fontAssets[i].filename) {
												addAsset = false;
												break;
											}
										}
										if (addAsset)
											bundleAssets.push(fontAssets[i]);
									} else
										bundleErrors.push(font);
								}
							}
							break;

						default:
							break;
					}
				});
			});
			Bundler.configurationErrors = _.unique(bundleErrors);
			return bundleAssets;
		};

		getScreen(screenid) {
			return this.bundle.screens.find(function(screen) {
				if (screen.screen == screenid)
					return screen;
			});
		};

		createScreen(screenid) {
			screenid = screenid || 1;
			var screen = this.getScreen(screenid);
			if (typeof screen == 'undefined') {
				this.bundle.screens.push({
					"screen": screenid,
					"menus": [],
					"hours": [],
					"timeslots": []
				});
			}
			return this;
		};

		removeScreen(screenid) {
			var screens = this.bundle.screens.filter(function(screen) {
				return screen.screen != screenid;
			});

			this.bundle.screens = screens;
			return this;
		};

		setScreenTimeslots(screenid, timeSlots) {
			this.getScreen(screenid).timeslots = timeSlots;
			this.getScreen(screenid).menus = [...new Set(_.pluck(timeSlots, 'menu'))];
			_.pluck(timeSlots, 'menu');
			this.getScreen(screenid).hours = _.pluck(timeSlots, 'hour');
			return this;
		};

		/**
		 * Rest Saving
		 */
		modelSynced(model) {
			logger.info('modelSynced: ', model);
			this.bundle.version = model.get('version');
			this.trigger({
				'type': 'sync',
				'message': this.statusMessage,
				'data': true
			});
		};

		syncConfiguration() {
			if (typeof this._model == 'undefined') {
				this._model = new Bundle();
				this._model.on('sync', this.modelSynced, this);
			}
			this.buildBundleConfiguration();

			if (this.status == Bundler.statusOptions.BUNDLE) {
				this._model.save({
					format: this.bundle.format,
					version: this.bundle.version,
					menus: _.pluck(bundler.bundle.menus, 'id').join(','),
					bundle: this._configuration
				});
			} else {
				this.trigger({
					'type': 'sync',
					'message': this.statusMessage,
					'data': false
				});
			}
		};
	}

	bundler = new Bundler();

	// Functions
	/**
	 * Send a Message to user
	 * Types: default, primary, success, warning, info, danger
	 */
	function notice(message, type) {
		return $.notify({
			message: message
		}, {
			type: type,
			z_index: 1060
		});
	} // notice

	// Templates
	var tmpBtnDelete = _.template(
		'<button type="button" data-screenid="screen-<%=screen%>" class="close" aria-label="Close"><span title="Delete Screen <%=screen%>?" data-toggle="tooltip" data-placement="left" class="delete" aria-hidden="true">×</span></button>'
	);

	// Drag & Drop Events
	function setDragImage(e, imgSrc) {
		var dragIcon = document.createElement('img');
		dragIcon.src = imgSrc;
		e.dataTransfer.setDragImage(dragIcon, -10, -10);
	}

	function handleDragStart(e) {
		logger.debug('handleDragStart');
		// none, copy, copyLink, copyMove, link, linkMove, move, all,
		// uninitialized
		e.dataTransfer.effectAllowed = 'copy';

		e.dataTransfer.setData("text", e.target.id);
		this.style.opacity = '0.4';
		setDragImage(e, icons.menu);
	}

	function handleDragEnd(e) {
		logger.debug('handleDragEnd:', e);
		this.style.opacity = '1';
	}

	function handleDragOver(e) {
		e.preventDefault();
		logger.debug('handleDragOver');
		// none, copy, link, move
		e.dataTransfer.dropEffect = 'copy';
		return false;
	}

	function handleDrop(e) {
		e.stopPropagation();
		e.preventDefault();
		logger.debug('handleDragEnd');
		var menu = document.getElementById(e.dataTransfer.getData("text"));

		menu.style.opacity = '1';

		if (this == menu.parentElement) {
			// Rearange menus
			screenMenu = menu;
		} else {
			// Adding a new menu
			var screenMenu = menu.cloneNode();
			screenMenu.setAttribute('data-screen', this.parentElement.getAttribute('data-screen'));
			screenMenu.setAttribute('data-menu', menu.id.replace('menu-', ''));
			screenMenu.setAttribute('data-hour', '0');
			screenMenu.id = I.UUID();
			screenMenu.innerText = menu.textContent;

			var timeSlot = document.createElement('input');
			timeSlot.classList.add('pull-right');
			timeSlot.classList.add('form-control');
			timeSlot.type = 'number';
			timeSlot.step = 1;
			timeSlot.min = 0;
			timeSlot.max = 23;
			timeSlot.value = 0;

			screenMenu.appendChild(timeSlot);

			timeSlot.addEventListener('change', handleHours, false);
			screenMenu.addEventListener('dragstart', handleDragStart, false);
			screenMenu.addEventListener('dragend', handleDragEnd, false);
		}

		// Check if we dropping on the header row
		if (e.target.tagName == 'UL') {
			$(this).prepend(screenMenu);
		} else {
			if ($(screenMenu).index() < $(e.target).index()) {
				screenMenu.querySelector('input').value = (e.target.querySelector('input').value * 1) + 1;
				$(e.target).after(screenMenu);
			} else {
				screenMenu.querySelector('input').value = (e.target.querySelector('input').value * 1);
				e.target.querySelector('input').value = (e.target.querySelector('input').value * 1) + 1;
				$(e.target).before(screenMenu);
			}
		}

		verifyHours(this);
		return false;
	}

	// BUTTONS
	function handleNewBundle(e) {
		screens.innerHTML='';
		bundler = new Bundler();

		if (!btnBuild.classList.contains('hide')) {
			btnBuild.classList.add('hide');
		}

		bundler.on('status', function(e) {
			logger.debug('on:status: ', e);
			if (e.data == 'ready') {
				btnBuild.classList.remove('hide');
			} else if (!btnBuild.classList.contains('hide')) {
				btnBuild.classList.add('hide');
			}
		});

		bundler.on('sync', handleBundleSynced);
	}

	function handleAddScreen(e) {
		var screen = document.createElement('ul');
		var screenId = screens.children.length + 1;
		screen.id = 'screen-' + screenId;
		screen.setAttribute('data-screen', screenId);
		screen.classList.add('list-group');
		screen.classList.add('screen');

		var btnDelete = tmpBtnDelete({
			screen: screenId
		});

		var title = document.createElement('li');
		$(title).append($('<h3>').text('Screen ' + screenId))
		var menus = document.createElement('ul');
		menus.classList.add('menus');

		$(screen).append($(btnDelete));
		screen.appendChild(title);
		screen.appendChild(menus);

		if (screens.children.length > 0)
			screens.children[screens.children.length - 1].querySelector('button').classList.toggle('hide');

		screens.appendChild(screen);

		screen.querySelector('button').addEventListener('click', handleDelete, false);
		menus.addEventListener('dragover', handleDragOver, false);
		menus.addEventListener('drop', handleDrop, false);

		$('[data-toggle="tooltip"]').tooltip('destroy');
		$('[data-toggle="tooltip"]').tooltip();

		statusMessage.innerText = bundler.createScreen(screenId).statusMessage;
	}

	function verifyHours(forScreen) {
		var hour = 0;
		var timeSlot = [];

		for (var i = 0; i < forScreen.children.length; i++) {
			if (Number.parseInt(forScreen.children[i].querySelector('input').value) < hour)
				forScreen.children[i].querySelector('input').value = hour;

			hour = Number.parseInt(forScreen.children[i].querySelector('input').value);
			forScreen.children[i].setAttribute('data-hour', hour);
			timeSlot.push({
				"menu": Number.parseInt(forScreen.children[i].getAttribute('data-menu')),
				"hour": hour
			});
			hour++;
		}

		statusMessage.innerText = bundler.setScreenTimeslots(forScreen.parentElement.getAttribute('data-screen'), timeSlot).statusMessage;
	}

	function handleHours(e) {
		verifyHours(e.target.parentElement.parentElement);
	}

	function handleDelete() {
		var screenid = this.getAttribute('data-screenid');
		document.getElementById(screenid).remove();
		statusMessage.innerText = bundler.removeScreen(screenid.replace('screen-', '')).statusMessage;

		if (screens.children.length > 0)
			screens.children[screens.children.length - 1].querySelector('button').classList.toggle('hide');
	}

	function adjustColumnLayout() {
		document.querySelector('.menubundle .menus-available .panel-default').style.maxHeight = (window.innerHeight - 300) + 'px';
		document.querySelector('.menubundle .menus-available .panel-heading').style.width = document.getElementById('menuRepository').offsetWidth + 'px';
		document.querySelector('.menubundle .menus-available .list-group').style.marginTop = document.querySelector(
			'.menubundle .menus-available .panel-heading').offsetHeight + 'px';
	}

	function handleBundleSynced(e) {
		logger.info('handleBundleSynced: ', e);

		btnNew.classList.remove('hide');
		btnAddScreen.classList.remove('hide');
		iconBuilding.classList.add('hide');
		if (e.data) {
			notice('Bundle Saved', 'success');
			//btnBuild.classList.remove('hide');
		} else
			notice('Bundle Errors', 'danger');
	}

	function handleBuildBundle() {
		btnAddScreen.classList.add('hide');
		btnBuild.classList.add('hide');
		btnNew.classList.add('hide');
		iconBuilding.classList.remove('hide');

		bundler.syncConfiguration();

	}

	// Events for draggable items
	var menus = document.querySelectorAll('.menu');
	[].forEach.call(menus, function(menu) {
		menu.addEventListener('dragstart', handleDragStart, false);
		menu.addEventListener('dragend', handleDragEnd, false);
	});

	document.getElementById('menuRepository').addEventListener('dragover', function(e) {
		e.preventDefault();
		logger.debug('handleDragOverDelete');
		// none, copy, link, move
		e.dataTransfer.dropEffect = 'move';
		return false;
	}, false);
	document.getElementById('menuRepository').addEventListener('drop', function(e) {
		e.stopPropagation();
		//if (e.target.parentElement != this)
		if (e.dataTransfer.getData("text").length > 10) {
			var screen = document.getElementById(e.dataTransfer.getData("text")).parentElement;
			document.getElementById(e.dataTransfer.getData("text")).remove();
			verifyHours(screen);
		}
		return false;
	}, false);

	btnNew.addEventListener('click', handleNewBundle, false);

	btnAddScreen.addEventListener('click', handleAddScreen, false);

	btnBuild.addEventListener('click', handleBuildBundle, false);

	statusMessage.innerText = bundler.statusMessage;

	bundler.on('status', function(e) {
		logger.debug('on:status: ', e);
		if (e.data == 'ready') {
			btnBuild.classList.remove('hide');
		} else if (!btnBuild.classList.contains('hide')) {
			btnBuild.classList.add('hide');
		}
	});

	bundler.on('sync', handleBundleSynced);

	$('#btnAddScreen').tooltip();
	$(btnNew).tooltip();

	adjustColumnLayout();
});
