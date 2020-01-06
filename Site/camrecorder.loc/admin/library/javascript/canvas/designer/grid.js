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
