/**
 *
 */

JSLoader.spinnerStart().loadModules(['menuclients', 'groups', 'animate']);

JSLoader.ready(function() {
	logger = Logger.get('Status');
	logger.setLevel(Logger.getDEBUG());
	//logger.setLevel(Logger.getINFO());
	
	/**
	 * Sync the menuclient table with database 
	 */
	var menuclients = new Menuclients();
	DBSync.staticCollection.groups = new Groups();
	DBSync.staticCollection.groups.fetch();

	var statusview = new StatusView({
		collection: menuclients,
		el: '#statusitems',
		logger: logger
	});
	
	/*
	menuclients.on('update', function(c, ms, e) {
		logger.debug('collection:update: ', c);
		statusview.render();
	});
	*/
	menuclients.on('add change remove', function(c, ms, e) {
		logger.debug('collection:update: ', c);
		statusview.render();
	});

	menuclients.fetch();
	DBSync.interval = 5000;
	DBSync.collection.push(menuclients);
	DBSync.start();

	/**
	 * Handle the menutree
	 */
	
	function handleGroupItemClick(e) {
		e.preventDefault();
		
		var item = this;
		var kid = item;
		var kids = [];
		var path = [];

		while (kid.id != 'clientgroupsmenu') {
			if (['A', 'LI'].indexOf(kid.tagName) >= 0) {
				kid.classList.add('active');
				if (kid.tagName == 'LI') {
					var kidButton = kid.children[0];
					kidButton.classList.add('active');
					path.push(kidButton.innerText);
					kids.push(kidButton);
				}
				kids.push(kid);
			}
			kid = kid.parentElement;
		}

		path.shift();
		document.querySelector('.status .group .info').innerText = path.join(' > ');
		document.querySelector('.status .group .name').innerText = item.text;

		I(document.getElementById('clientgroupsmenu').getElementsByClassName('active')).each(function(v, i) {
			if (kids.indexOf(v) == -1)
				v.classList.remove('active');
		});
		
		ids = I(($(this).data('idg') + ',' + $(this).data('subgroups')).split(',')).without('');
		
		if ($(this).data('idg') == '0')
			statusview.setGroupIds([]);
		else
			statusview.setGroupIds(ids);
	}

	I(document.getElementsByClassName('groupitem')).each(function(v, i) {
		v.addEventListener('click', handleGroupItemClick, false);
	});

	document.getElementsByClassName('groups')[0].children[0].children[1].style.height = window.innerHeight - 400 + 'px';
	document.getElementById('clientgroupsmenu').querySelector('A').click();
});
