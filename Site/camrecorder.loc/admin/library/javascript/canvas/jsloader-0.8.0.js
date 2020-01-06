/**
 * JSLoader
 *
 * @version 0.8.0
 * @author Philip<peep@cathedral.co.za>
 *
 * Public Domain.
 * NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.
 */

/**
 * EVENTS
 * add
 *
 * start
 *
 * queueing
 * queued
 *
 * skipped
 * loaded
 * error
 *
 * done
 */

/**
 * Change Log
 *
 * 0.8.0 (2016 May 26)
 *  ? Fix Loop   : Multi load required modules
 *  - Ready      : Register callbacks for done
 *
 * 0.7.0 (2016 May 18)
 *  - history    : Keep history of loaded files, prevent double loading
 *  - event & log: Fixed a problem with css files sent as empty string to events
 *  - other      : Little tweaks to speed things up
 *
 * 0.6.0 (2016 Apr 29)
 *  - noCache    : uses a query string to stop browser caching of stylesheets
 *  - Spinner    : optional loading spinner while scripts loading (only handy for large queues)
 *   - Underscore: removed dependance (next backbone?)
 */

(function(factory) {
	var root = (typeof self == 'object' && self.self == self && self) || (typeof global == 'object' && global.global ==
		global && global);

	if (typeof root.JSLoader !== 'undefined') {
		console.log('JSLoader already loaded!');
		return;
	}

	var JSLoader = {};
	_.extend(JSLoader, Backbone.Events);

	root.JSLoader = factory(root, JSLoader, root.Backbone, (root.jQuery || root.Zepto || root.ender || root.$));
}(function(root, JSLoader, Backbone, $) {
	JSLoader.VERSION = '0.8.0';
	JSLoader.verbose = document.currentScript.getAttribute('data-verbose') == 1 ? true : false;

	// Holds the modules defined in jsloader-modules.js
	JSLoader.moduleList = {};

	// When true (default: false) adds ?ts=timestamp to style to avoid caching
	JSLoader.noCache = false;

	// When true JSLoader will call stop spinner when done
	JSLoader.autospinner = true;

	var processQueue = [];
	var scriptCollection = [];
	
	var transTime = 2000;

	var history = [];

	$.holdReady(true);

	/**
	 * FUNCTIONS
	 */

	/**
	 * Removes all instances of values from array and returns new array
	 *
	 * @param array		Source array to remove values from
	 * @param values	String or Array with values to remove
	 *
	 * @return array
	 */
	function without(array, values) {
		if (!Array.isArray(values)) values = [values];
		return array.filter(function(element) {
			return values.indexOf(element) < 0;
		});
	} // without

	/**
	 * Each
	 */
	var each = Array.prototype.forEach;

	/**
	 * LOGGING
	 */

	/**
	 * Turn logging to console on or off
	 *
	 * @param enabled	bool
	 */
	JSLoader.enableVerbosity = function(enabled) {
			if (enabled === false)
				JSLoader.verbose = false;
			else
				JSLoader.verbose = true;

			return JSLoader;
		} // enableVerbosity

	/**
	 * Logs msg if verbosity OR show true
	 *
	 * @param msg
	 * @param show
	 * @returns {String}
	 */
	function logger(msg, show) {
		if ((JSLoader.verbose === true) || (show === true)) console.log('JSLoader: ' + msg);
		return JSLoader;
	} // logger

	/**
	 * About JSLoader
	 *
	 * Show some basic JSLoader info
	 */
	JSLoader.about = function() {
			logger(
				`
Author: Philip Michael Raab<peep@cathedral.co.za>
Version: ${JSLoader.VERSION}
Script verbosity attribute: 1`);
		} // about

	/**
	 * Ready
	 */

	this._ready = [];

	JSLoader.ready = function(listener) {
		if (typeof JSLoader._ready == "undefined") {
			JSLoader._ready = [];
		}

		JSLoader._ready.push(listener);
	}

	removeReady = function(listener) {
		if (JSLoader._ready instanceof Array) {
			var listeners = JSLoader._ready;
			for (var i = 0, len = listeners.length; i < len; i++) {
				if (listeners[i] === listener) {
					listeners.splice(i, 1);
					break;
				}
			}
		}
	}

	runReady = function() {
		logger('loading => ready');
		if (JSLoader._ready instanceof Array) {
			var listeners = JSLoader._ready;
			var listener;
			//for (var i = 0, len = listeners.length; i < len; i++) {
			while (listener = listeners.shift()) {
				listener.call(this);
				removeReady(listener);
			}
		}
	}

	/**
	 * Spinner
	 */
	JSLoader.spinner = false;

	/**
	 * Set autospinner on|off
	 *
	 * @param enabled	bool
	 */
	JSLoader.setAutospinner = function(enabled) {
			if (enabled === false) {
				JSLoader.autospinner = false;
			} else {
				JSLoader.autospinner = true;
			}
			return JSLoader;
		} // setAutospinner

	/**
	 * Adds spinner to body and starts it
	 *
	 * @returns JSLoader
	 */
	JSLoader.spinnerStart = function() {
			if (JSLoader.spinner === false) {
				JSLoader.spinner = document.createElement('div');
				JSLoader.spinner.classList.add('jsl-fading-circle');

				JSLoader.spinner.innerHTML =
					`
<div class="jsl-circle1 jsl-circle"></div>
<div class="jsl-circle2 jsl-circle"></div>
<div class="jsl-circle3 jsl-circle"></div>
<div class="jsl-circle4 jsl-circle"></div>
<div class="jsl-circle5 jsl-circle"></div>
<div class="jsl-circle6 jsl-circle"></div>
<div class="jsl-circle7 jsl-circle"></div>
<div class="jsl-circle8 jsl-circle"></div>
<div class="jsl-circle9 jsl-circle"></div>
<div class="jsl-circle10 jsl-circle"></div>
<div class="jsl-circle11 jsl-circle"></div>
<div class="jsl-circle12 jsl-circle"></div>`;
			}

			JSLoader.spinner.style.width = window.innerWidth + 'px';
			JSLoader.spinner.style.height = window.innerHeight + 'px';
			//document.body.prepend(JSLoader.spinner);
			document.body.appendChild(JSLoader.spinner);
			JSLoader.spinner.style.opacity = 1;
			logger('spinner => start');

			return JSLoader;
		} // spinnerStart

	/**
	 * Adds spinner to body and starts it
	 *
	 * @returns JSLoader
	 */
	JSLoader.spinnerStop = function() {
			if (JSLoader.spinner !== false) {
				JSLoader.spinner.style.opacity = 0;
				setTimeout(function() {
					JSLoader.spinner.remove();
					logger('spinner => stop');
				}, this.transTime);
			}
			return JSLoader;
		} // spinnerStop

	/**
	 * HISTORY
	 */

	/**
	 * Return array of loaded scripts
	 */
	JSLoader.getHistory = function() {
		return history;
	}

	/**
	 * Returns true if script already loaded
	 */
	JSLoader.hasHistory = function(script) {
		return history.indexOf(script) >= 0 ? true : false
	}

	/**
	 * Adds script to history if no recored exists
	 */
	addHistory = function(script) {
		JSLoader.hasHistory(script) || history.push(script);
		return JSLoader;
	}

	/**
	 * Module Management
	 */

	// TODO: Create dependance chart to ensure no module gets loaded before its requierments
	/*
	function* moduleGenerator() {
		for (mod of I(JSLoader.moduleList).allKeys()) {
			yield JSLoader.moduleList[mod];
		}
	}

	JSLoader.testModList = function() {
		var genny = moduleGenerator();
		for (var mod of genny) {
			console.log(mod);
		}
	}
	*/

	/**
	 * dependancy
	 */
	JSLoader.checkDependants = _.once(function() {
		var keys = I(JSLoader.moduleList).allKeys();
		keys.forEach(function(module) {
			var requs = JSLoader.moduleList[module].require || [];
			requs.forEach(function(mod) {
				var deps = JSLoader.moduleList[mod].dependents || [];
				deps.push(module);
				deps.sort();
				JSLoader.moduleList[mod].dependents = deps;
			});
		});
	})

	/**
	 * LOADING SCRIPTS
	 */

	/**
	 * Add script urls to queue
	 *
	 * @deprecated
	 * @param scripts	array
	 * @param priority	int
	 */
	JSLoader.addScripts = function(scripts, priority) {
		logger('DEPRECATED => addScripts', true);
		return addToCollection(scripts, priority);;
	}

	/**
	 * Add script urls to collection
	 *
	 * @param scripts	array
	 * @param priority	int
	 */
	function addToCollection(scripts, priority) {
		if (scripts.length > 0) {
			if (typeof priority === 'undefined') priority = 10;

			if (!Array.isArray(scriptCollection[priority])) scriptCollection[priority] = [];

			scriptCollection[priority] = scriptCollection[priority].concat(scripts);
			JSLoader.trigger('add', scripts, priority);
			logger('added => ' + scripts.length);
		}
		return JSLoader;
	}

	/**
	 * Adds a module to the queue for loading
	 *
	 * @param modules	array
	 * @param priority	int
	 */
	JSLoader.loadModules = function(modules, priority) {
		if (modules.length > 0) {
			var scripts = [];

			each.call(modules, function(module, index) {
				if (JSLoader.moduleList[module].loaded !== true) {
					if (Array.isArray(JSLoader.moduleList[module].require)) {
						logger('require => ' + JSLoader.moduleList[module].require.join(', '));
						JSLoader.loadModules(JSLoader.moduleList[module].require, priority);
					}
					sFiles = [JSLoader.moduleList[module].style, JSLoader.moduleList[module].script];

					scripts = scripts.concat(without(sFiles, undefined));
					JSLoader.moduleList[module].loaded = true;
				}
			});

			addToCollection(scripts, priority);
		}
		return JSLoader;
	}

	/**
	 * Loading scripts into dom
	 */

	/**
	 * Loading a module filed
	 *
	 * @param _callback	function
	 */
	JSLoader.fail = function(_callback) {
		JSLoader.trigger('error', _callback);
		return JSLoader;
	}

	/**
	 * Adds a stylesheet to the dom
	 *
	 * @param script	string
	 * @param _callback	function
	 */
	function getStyle(script, _callback) {
		this.url = script;

		$("<link/>", {
			rel: "stylesheet",
			type: "text/css",
			href: script,
			success: _callback(this, 'success', script)
		}).appendTo("head");

		return JSLoader;
	}

	/**
	 * Checks the type of file and returns the loader for that type
	 *
	 * @param file	string
	 */
	function getHandler(file) {
		var ext = file.split('.').pop();
		if (ext === 'js') {
			return $.getScript;
		} else if (ext === 'css') {
			return getStyle;
		}

		return false;
	}

	/**
	 * Processing requests
	 */

	/**
	 * Adds scripts to queue by priority and unique
	 * Returning true if there are items to process
	 *
	 * @returns {bool}
	 */
	function createQueue() {
		logger('loading => queue');
		JSLoader.trigger('queueing');

		merged = processQueue;

		collection = scriptCollection;
		scriptCollection = [];

		each.call(collection, function(scripts, key) {
			if (typeof scripts !== 'undefined') {
				merged = merged.concat(scripts);
			}
		});
		processQueue = [...new Set(merged)];

		if (processQueue.length > 0) {
			logger('queued => ' + processQueue.length);
			JSLoader.trigger('queued', processQueue.length);
			return true;
		}

		return false;
	}

	/**
	 * Processes a batch of modules to add to dom
	 *
	 * @param scripts	array
	 */
	function process(scripts) {
		if (scripts.length > 0) {
			$.holdReady(true);
			aScript = scripts.shift(); // + '?' + Date.now();

			if (JSLoader.hasHistory(aScript)) {
				logger('duplicate => ' + script);
				JSLoader.trigger('skipped', script);
				process(scripts);
				$.holdReady(false);
			} else {
				fileHandler = getHandler(aScript);
				aScript += JSLoader.noCache ? '?ts=' + Date.now() : '';

				fileHandler(aScript, function(data, textStatus, jqxhr) {
					script = this.url.split('?').shift();
					addHistory(script);
					logger('success => ' + script);
					JSLoader.trigger('loaded', script);
					process(scripts);
					$.holdReady(false);
				}).fail(function(jqxhr, settings, exception) {
					script = this.url.substring(0, this.url.indexOf('?'));
					logger('Failed => ' + script + ' => ' + exception.message, true);
					JSLoader.trigger('error', {
						script: script,
						error: exception
					});
					process(scripts);
					$.holdReady(false);
				});
			}
		} else {
			if (createQueue()) process(processQueue);

			$.holdReady(false);
			logger('loading => done');
			JSLoader.trigger('done');

			runReady();

			if (JSLoader.autospinner) JSLoader.spinnerStop();
		}
	}

	/**
	 * Start JSLoader loading the added scripts
	 * events: add, start, queueing, queued, loaded, error, done
	 *
	 * @returns {String}
	 */
	JSLoader.start = function() {
		logger('loading => start');
		JSLoader.trigger('start');

		JSLoader.checkDependants();
		if (createQueue()) process(processQueue);

		return JSLoader;
	}

	root.document.onreadystatechange = function() {
		//console.info('onreadystatechange: ', root.document.readyState);
		if (root.document.readyState == 'interactive') {
			JSLoader.start();
		}
	}

	/* Show about if script verbosity attribute set to 1 */
	JSLoader.about();

	return JSLoader;
}));
