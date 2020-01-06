/**
 * Site functions
 */

/**
 * Generate tree menu
 *
 * @param menu
 * @param tree
 * @returns {String}
 */

(function() {
	if (!APP_ENV.development())
		JSLoader.enableVerbosity(false);
	
	if (navigator.appVersion.indexOf('Win')!=-1) APP_ENV.OSName='Windows';
	if (navigator.appVersion.indexOf('Mac')!=-1) APP_ENV.OSName='MacOS';
	if (navigator.appVersion.indexOf('X11')!=-1) APP_ENV.OSName='UNIX';
	if (navigator.appVersion.indexOf('Linux')!=-1) APP_ENV.OSName='Linux';

	JSLoader.loadModules(['extend'], 1).loadModules(['shortcuts']);
	var logLevel = document.currentScript.getAttribute('logger-level') ? document.currentScript.getAttribute('logger-level') : 99;
	logLevel = APP_ENV.development() ? logLevel : 99;

	var logName = [];
	logName[1] = 'DEBUG';
	logName[2] = 'INFO';
	logName[4] = 'WARN';
	logName[8] = 'ERROR';
	logName[99] = 'OFF';

	if (logName[logLevel] !== undefined) {
		APP_ENV.logLevel.value = logLevel;
		APP_ENV.logLevel.name = logName[logLevel];
	}

	Logger.reqLevel = function(logLevel) {
		if (APP_ENV.development())
			return logLevel;
		return APP_ENV.logLevel;
	}

	Logger.getDEBUG = function() {
		return Logger.reqLevel(Logger.DEBUG);
	}

	Logger.getINFO = function() {
		return Logger.reqLevel(Logger.INFO);
	}

	Logger.getWARN = function() {
		return Logger.reqLevel(Logger.WARN);
	}

	Logger.getERROR = function() {
		return Logger.reqLevel(Logger.ERROR);
	}

	/*APP_ENV.requestLogLevel = function(logLevel) {
		if (APP_ENV.development())
			return logLevel;
		return APP_ENV.logLevel;
	}*/
})();

JSLoader.ready(function() {
	Logger.useDefaults({
		formatter: function(messages, context) {
			messages.unshift('[' + APP_ENV.name + ']');
			if (context.name)
				messages.unshift('[' + context.name + ']');
		}
	});

	// Logger.setLevel(Logger.OFF);

	// Logger.setLevel(Logger.OFF); //99
	// Logger.setLevel(Logger.DEBUG); //1
	// Logger.setLevel(Logger.INFO); //2

	// Logger.setLevel(Logger.WARN); //4
	// Logger.setLevel(Logger.ERROR); //8

	Logger.setLevel(APP_ENV.logLevel);

	shortcut.add('ctrl+alt+v', function() {
		APP_ENV.footer = document.getElementById('APP_ENV').innerHTML;

		$('#APP_ENV').fadeOut(1000, function() {
			document.getElementById('APP_ENV').innerHTML = "<abbr title='attribute'>" + APP_ENV.v + '</abbr> @ <strong>Env:</strong> ' + APP_ENV.env +', <strong>HOST:</strong> ' + APP_ENV.host;
			$('#APP_ENV').fadeIn(1000, function() {
				setTimeout(function() {
					$('#APP_ENV').fadeOut(1000, function() {
						document.getElementById('APP_ENV').innerHTML = APP_ENV.footer;
						$('#APP_ENV').fadeIn(1000);
					});
				}, 5000);
			});
		});
	});
	
	/**
     * Short-Cuts
     * 
     * ctrl+alt+o : Open
     * ctrl+alt+s : Status
     * ctrl+alt+n : New
     * ctrl+alt+b : Bundle
     * ctrl+alt+f : Fonts
     */
	
	shortcut.add('ctrl+alt+o', function() {
		location.href = APP_ENV.routes.designer.open;
	});

	shortcut.add('ctrl+alt+s', function() {
		location.href = APP_ENV.routes.status;
	});

	shortcut.add('ctrl+alt+n', function() {
		location.href = APP_ENV.routes.designer.create;
	});

	shortcut.add('ctrl+alt+b', function() {
		location.href = APP_ENV.routes.bundle;
	});

	shortcut.add('ctrl+alt+f', function() {
		location.href = APP_ENV.routes.fonts;
	});
}, this);

window.onerror = function(msg, url, lineNo, columnNo, error) {
	var string = msg.toLowerCase();
	var substring = 'script error';
	if (string.indexOf(substring) > -1) {
		alert('Script Error: See Browser Console for Detail');
	} else {
		alert(msg, url, lineNo, columnNo, error);
	}
	return false;
};
