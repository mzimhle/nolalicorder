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
