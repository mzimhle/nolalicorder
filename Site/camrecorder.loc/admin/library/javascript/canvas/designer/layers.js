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
