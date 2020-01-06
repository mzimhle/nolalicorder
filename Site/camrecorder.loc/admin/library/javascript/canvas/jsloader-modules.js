/**
 * Setup the modules for JSLoader
 */

//JSLoader.modules({
JSLoader.moduleList = {
		xdate: {
			script: '/library/javascript/canvas/extend/date.js'
		},
		xnumber: {
			script: '/library/javascript/canvas/extend/number.js'
		},
		xstring: {
			script: '/library/javascript/canvas/extend/string-1.3.0.js'
		},
		xobject: {
			script: '/library/javascript/canvas/extend/object-1.0.1.js'
		},
		extend: {
			require: ['xdate', 'xnumber', 'xstring', 'xobject']
		},
		dbsync: {
			script: '/library/javascript/canvas/db/dbsync.js'
		},
		aclroles: {
			script: '/library/javascript/canvas/db/model/aclroles.js',
			require: ['dbsync']
		},
		groups: {
			script: '/library/javascript/canvas/db/model/groups.js',
			require: ['dbsync']
		},
		menus: {
			script: '/library/javascript/canvas/db/model/menus.js',
			require: ['dbsync']
		},
		bundles: {
			script: '/library/javascript/canvas/db/model/bundles.js',
			require: ['dbsync']
		},
		users: {
			script: '/library/javascript/canvas/db/model/users.js',
			require: ['dbsync']
		},
		menuclients: {
			script: '/library/javascript/canvas/db/model/menuclients.js',
			require: ['dbsync', 'statusview']
		},
		cssfonts: {
			script: '/library/javascript/canvas/db/model/cssfonts.js',
			require: ['dbsync']
		},
		cssfontview: {
			script: '/library/javascript/canvas/db/view/cssfontview.js',
			require: ['cssfonts', 'bootbox']
		},
		statusview: {
			script: '/library/javascript/canvas/db/view/statusview.js'
		},
		ocanvas: {
			script: '/library/javascript/canvas/lib/ocanvas-2.8.4.min.js'
		},
		imagepicker: {
			script: '/library/javascript/canvas/lib/image-picker-0.2.4.min.js',
			style: '/css/image-picker-0.2.4.css'
		},
		jqueryform: {
			script: '/library/javascript/canvas/lib/jquery.form-3.51.0.js'
		},
		jscolor: {
			script: '/library/javascript/canvas/lib/jscolor-2.0.4.min.js'
		},
		spin: {
			script: '/library/javascript/canvas/spin-2.3.2.min.js'
		},
		bootbox: {
			script: '/library/javascript/canvas/lib/bootbox-4.4.0.min.js'
		},
		shortcuts: {
			script: '/library/javascript/canvas/lib/shortcut-2.01.B.js'
		},
		animate: {
			style: '/css/animate-3.5.1.min.css'
		},
		imagegallery: {
			script: '/library/javascript/canvas/image-gallery.js',
			style: '/css/image-gallery.css',
			require: ['jqueryform']
		},
		fileinput: {
			script: '/library/javascript/canvas/lib/fileinput-4.3.2.min.js',
			style: '/css/fileinput-4.3.2.min.css'
		},
		contextMenu: {
			script: '/library/javascript/canvas/lib/contextMenu-1.4.1.min.js',
			style: '/css/contextMenu-1.4.1.css'
		},
		notify: {
			script: '/library/javascript/canvas/lib/bootstrap-notify-3.1.5.min.js',
			require: ['animate']
		},
		userview: {
			script: '/library/javascript/canvas/db/view/userview.js',
			require: ['users', 'animate', 'bootbox']
		},
		infoview: {
			script: '/library/javascript/canvas/db/view/infoview.js',
			require: ['menus', 'animate']
		},
		mdlayout: {
			script: '/library/javascript/canvas/designer/layout.js'
		},
		mdlayers: {
			script: '/library/javascript/canvas/designer/layers.js'
		},
		mdgrid: {
			script: '/library/javascript/canvas/designer/grid.js'
		},
		mdmenu: {
			script: '/library/javascript/canvas/designer/menu.js'
		},
		designerui: {
			require: ['mdmenu', 'mdgrid', 'mdlayers', 'mdlayout']
		},
		designer: {
			script: '/library/javascript/canvas/designer.bundle.min.js',
			require: ['ocanvas', 'jscolor', 'notify', 'imagegallery', 'infoview', 'imagepicker']
		}
	} //);