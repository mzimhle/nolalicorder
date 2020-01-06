/* global oCanvas, jscolor, Logger */

/**
 * Designer Used to build the menu screens via a web application
 */

JSLoader.spinnerStart().loadModules(['designer']);

function startDebugger() {
    debugger;
} // startDebugger

var mbd = {
    property: null,
    fontHelper: null,
    toolbar: null,
    propertyEditor: null,
    defautls: {
        font: {
            font: 'normal normal normal 32px Arial',
            colour: '#000'
        }
    },
    data: {
        model: null,
        collection: null,
    },
    cursor: {
        normal: 'crosshair',
        move: 'move',
        resize: 'nesw-resize',
        resize_x: 'ns-resize',
        resize_y: 'ew-resize'
    },
    shifted: false,
    ctrled: false
};

var designer;

$(document).on('keyup keydown', function(e) {
    mbd.shifted = e.shiftKey;
    mbd.ctrled = e.ctrlKey;

    if (mbd.shifted)
        document.getElementById('key-shift').classList.add('alert-success');
    else
        document.getElementById('key-shift').classList.remove('alert-success');

    if (mbd.ctrled)
        document.getElementById('key-ctrl').classList.add('alert-success');
    else
        document.getElementById('key-ctrl').classList.remove('alert-success');
});

JSLoader.ready(function() {
	mbd.logger = Logger.get('Canvas');
	mbd.logger.setLevel(Logger.getDEBUG());
	
    var template = {};
    template.property = {};
    template.property.input = _
        .template(
            `<div class='md-property'>
				<span class='text-capitalize'><%=property%></span>
				<input class='md-input prop-<%=property%>' id='prop-<%=property%>' type='<%=type%>' value='<%=value%>'<%=minMax%>/>
			</div>`
        );

    designer = new Designer();
    designer.canvas.mouse.cursor(mbd.cursor.normal);
    document.getElementById('gridSnap').value = designer.gridscene.snapTo;
    
    document.getElementById('gridLines').addEventListener('click', function() {
        designer.gridscene.visible = this.checked;
    });

    jscolor.installByClassName('shadow-colour');

    $('input[type=number]').on('change', function() {
        value = valueTest = $(this).val() * 1;
        if (isNaN(valueTest)) {
            valueTest = valvalueTestue.replace(/\D/g, '');
        }
        if (valueTest == '') {
            dValue = $(this).data('default');
            valueTest = dValue ? dValue : this.min;
        }

        if (valueTest < this.min)
            valueTest = this.min;
        if (valueTest > this.max)
            valueTest = this.max;
        if (valueTest != value)
            $(this).val(valueTest);

        if (this.id == 'gridSnap') {
            designer.gridscene.createGrid({
                snapTo: valueTest * 1
            });
        }

        if (this.id == 'canvasZoom') {
            $('#canvas').css('width', $(this).data('width') / (100 / valueTest));
            $('#canvas').css('height', $(this).data('height') / (100 / valueTest));
        }
    });

    /**
     * FontHelper
     */
    function FontHelper() {
        this.fontString = null;
        this.fontItalic = 'normal'; // italic
        this.fontOblique = 'normal'; // oblique
        this.fontBold = 'normal'; // bold
        this.fontPx = 'px';
        this.fontSize = 32;
        this.fontFamily = 'Arial';

        this.fonts = ['Arial', 'Comic Sans MS', 'Courier New', 'Georgia', 'Impact', 'Times New Roman', 'Trebuchet MS', 'Verdana'];

        this.fonts = uf.concat(this.fonts);

        /**
         * Gets the font as an Array Optional param fontString
         * to create the array from If left empty with use last
         * used font
         */
        this.getFontArray = function(frontString) {
            // If we got a fontString process it
            if (typeof frontString === 'string') {
                frontArray = frontString.split(' ');
                if (frontArray.length > 5) {
                    subPart = frontArray.splice(4, frontArray.length);
                    frontArray.push(subPart.join(' '));
                }

                // If the basic parts seem valid save it
                if ((this.fonts.indexOf(frontArray[4]) >= 0) && (frontArray[3].indexOf('px') >= 1)) {
                    this.fontItalic = frontArray[0];
                    this.fontOblique = frontArray[1];
                    this.fontBold = frontArray[2];
                    this.fontPx = frontArray[3].substring(frontArray[3].indexOf('px'));
                    this.fontSize = frontArray[3] = frontArray[3].replace(this.fontPx, '');
                    this.fontFamily = frontArray[4];
                } else {
                    delete frontArray;
                }
            }

            // Either the fontString was invalid or
            //  we did not get one
            //  So we use the saved font
            if ((typeof frontArray === 'undefined') || (!Array.isArray(frontArray)))
                frontArray = [this.fontItalic, this.fontOblique, this.fontBold, this.fontSize.toString(), this.fontFamily];

            return frontArray;
        }; // getFontArray

        /**
         * Gets the font as an string Optional param fontArray
         * to create the string from If left empty with use last
         * used font
         */
        this.getFontString = function(frontArray) {
            if ((typeof frontArray === 'undefined') || (!Array.isArray(frontArray))) {
                frontArray = [];
                frontArray.push(this.fontItalic, this.fontOblique, this.fontBold, this.fontSize.toString(), this.fontFamily);
            }

            frontArray[3] = frontArray[3] + this.fontPx;
            this.fontString = frontArray.join(' ');
            return this.fontString;
        }; // getFontString

        this.getFontArray(mbd.defautls.font.font);
    } // FontHelper
    
    mbd.fontHelper = new FontHelper();

    /**
     * @Toolbar
     */
    function Toolbar() {
        _.extend(this, Backbone.Events);

        var Button = Backbone.Model.extend({
            idAttribute: 'type',
            debug: false,
            defaults: {
                'type': null,
                'group': '',
                'title': null,
                'icon': null,
                'text': '',
                'disabled': false
            },
            initialize: function() {
                this.on('add', function(model) {
                    this.log('Button::Add::' + model.attributes.id);
                });
                this.on('change', function(model, state) {
                    this.log('Button::change::' + model.attributes.id);
                });
                this.on('remove', function(model, state) {
                    this.log('Button::remove::' + model.attributes.id);
                });
            },
            log: function(message) {
                if (this.debug === true)
                    console.log(message);
            }
        });

        var View = Backbone.View
            .extend({
                tagName: 'button',
                className: 'toolbar btn btn-default ',
                debug: false,
                events: {
                    'click': 'onClick',
                },
                initialize: function() {},
                render: function() {
                    this.$el.html(this.template(this.model.attributes));
                    if (this.model.attributes.disabled)
                        this.$el.addClass('disabled');
                    $('#group-' + this.model.attributes.group).append(this.$el);
                    return this;
                },
                
                template: _.template(
                    `<span data-toggle="tooltip" data-placement="bottom" title="<%=title%>" class="" data-type="<%=type%>">
						<span style="padding-left: 3px;"><img alt="" src="/images/designer/toolbar/<%=icon%>.png" class="btn-designer animated infinite"></span>
					</span>`
                ),
                onClick: function() {
                    this.trigger('click', this.model.attributes);
                    this.log('viewGroupFile::click');
                    this.log(this);
                },
                setState: function(state) {
                    if (state) {
                        this.el.classList.remove('btn-default');
                        this.el.classList.add('btn-danger');
                    } else {
                        this.el.classList.remove('btn-danger');
                        this.el.classList.add('btn-default');
                    }
                },
                log: function(message) {
                    if (this.debug === true)
                        console.log(message);
                }
            });

        function proxyEvent(event, args) {
            this.trigger(event, args);
        } // proxyEvent

        /*
         * File
         */
        this.vSave = new View({
            model: new Button({
                type: 'save',
                group: 'file',
                title: 'Save [ALT+SHIFT+S]',
                icon: 'save',
                disabled: false
            })
        });

        this.listenTo(this.vSave, 'all', proxyEvent);
        this.vSave.render();

        /*
         * Objects
         */
        var vText = new View({
            model: new Button({
                type: 'text',
                group: 'objects',
                title: 'Text [CTRL+SHIFT+T]',
                icon: 'text',
                text: 'Text'
            })
        });

        var vLine = new View({
            model: new Button({
                type: 'line',
                group: 'objects',
                title: 'Line [CTRL+SHIFT+L]',
                icon: 'line',
                text: 'Line'
            })
        });

        var vImage = new View({
            model: new Button({
                type: 'image',
                group: 'objects',
                title: 'Image [CTRL+SHIFT+I]',
                icon: 'image',
                text: 'Image'
            })
        });

        this.listenTo(vText, 'all', proxyEvent);
        this.listenTo(vLine, 'all', proxyEvent);
        this.listenTo(vImage, 'all', proxyEvent);
        vText.render();
        vLine.render();
        vImage.render();

        var vInfo = new View({
            model: new Button({
                type: 'info',
                group: 'info',
                title: 'Info',
                icon: 'info',
                text: ''
            })
        });
        this.listenTo(vInfo, 'all', proxyEvent);
        vInfo.render();
    } // Toolbar
    mbd.toolbar = new Toolbar();

    /**
     * PropertyEditor
     */
    function PropertyEditor() {
        _.extend(this, Backbone.Events);

        $('.property-editor').hide();
        $('.shadow-heading').hide();

        this.priotity = {
            // Emergency: system unusable
            'EMERG': 0,
            // Alert: action taken immediately
            'ALERT': 1,
            // Critical: critical conditions
            'CRIT': 2,
            // Error: error conditions
            'ERR': 3,
            // Warning: warning conditions
            'WARN': 4,
            // Notice: normal but significant
            'NOTICE': 5,
            // Informational: informational messages
            'INFO': 6,
            // Debug: debug messages
            'DEBUG': 7
        };

        this.activity = {
            'propertybar': false,
            'verbosity': this.priotity.DEBUG
        }; // activity

        this.caps = ['butt', 'round', 'square'];

        /**
         * Logger, mostly for debug stuff
         */
        /*this.logger = function(msg, priority) {
            var Description = ['Emergency', 'Alert', 'Crit', 'Error', 'Warn', 'Notice', 'Info', 'Debug'];
            if (!priority)
                priority = mbd.propertyEditor.priotity.INFO;
            if (priority <= mbd.propertyEditor.activity.verbosity)
                console.log(Description[priority] + ': ' + msg);
        }*/

        //this.logger = Logger.get('PropertyEditor');
        //this.logger.setLevel(Logger.getDEBUG());

        /**
         * Make sure we don't get UI conflicts
         */
        this.waitForUI = function(newProcess) {
            if (mbd.propertyEditor.activity.propertybar) {
                setTimeout(function() {
                    mbd.propertyEditor.waitForUI();
                }, 100);
            } else {
                if (newProcess)
                    mbd.propertyEditor.activity.propertybar = true;
            }
        } // waitForUI

        /**
         * Lets us change display object properties
         */
        this.propertyBarCreate = function(widget) {
                this.waitForUI(true);
                if (widget && widget.hasOwnProperty('core')) {
                    if (widget.type === 'text') {
                        var fontParts = mbd.fontHelper.getFontArray(widget.font);
                        var fntIdx = mbd.fontHelper.fonts.indexOf(fontParts[4]);

                        bold = false;
                        if (mbd.fontHelper.fontBold === 'bold')
                            bold = true;

                        italic = false;
                        if (mbd.fontHelper.fontItalic === 'italic')
                            italic = true;

                        oblique = false;
                        if (mbd.fontHelper.fontOblique === 'oblique')
                            oblique = true;

                        this.propertyAddInput('text', widget.text, 'text');
                        this.propertyAddInput('size', mbd.fontHelper.fontSize, 'number');
                        this.propertyAddInput('colour', widget.fill, 'text');
                        this.propertyAddSelect('font', fntIdx, 'number', mbd.fontHelper.fonts);
                        this.propertyAddCheckbox('bold', bold);
                        this.propertyAddCheckbox('italic', italic);
                        jscolor.installByClassName('prop-colour');
                        this.updateShadow(widget.shadow);
                    } else if (widget.type === 'line') {
                        var capIdx = mbd.propertyEditor.caps.indexOf(widget.cap);
                        var theStroke = widget.stroke.split(' ');
                        this.propertyAddInput('size', theStroke[0].replace('px', ''), 'number');
                        this.propertyAddInput('colour', theStroke[1], 'text');
                        this.propertyAddSelect('cap', capIdx, 'number', mbd.propertyEditor.caps);
                        jscolor.installByClassName('prop-colour');
                        this.updateShadow(widget.shadow);
                    } else if (widget.type === 'image') {
                        this.updateShadow(widget.shadow);
                        // this.propertyAddSelect('image', widget.image,
                        // 'image', []);
                    }

                    this.updatePosition(widget);

                    this.propertyAddInput('rotation', widget.rotation, 'number');
                    this.propertyAddInput('layer', widget.zIndex - designer.gridscene.lines, 'number');

                    $('#property-type').text(widget.type);
                    $('#property-type').data('id', widget.id);
                    if (!$('.property-editor').is(':visible'))
                        $('.property-editor').toggle('slide');
                    this.activity.propertybar = false;
                }
            } // propertyBarCreate

        this.propertyBarDestroy = function(_callback) {
            //this.logger.debug('propertyBarDestroy::start');
            this.waitForUI(true);
            if ($('.property-editor').is(':visible')) {
                $('.property-editor').toggle('slide', function() {
                    $('.property-list').empty();
                    $('#property-type').text('');
                    $('#property-type').attr('data-id', '');
                    $('.shadow-heading').hide();
                    mbd.propertyEditor.activity.propertybar = false;
                    if (_callback)
                        _callback();
                });
            } else {
                this.activity.propertybar = false;
                if (_callback)
                    _callback();
            }
        } // propertyBarDestroy

        this.propertyBarIsEmpty = function() {
            //this.logger.debug('propertyBarIsEmpty::start');
            this.waitForUI();
            if ($('#property-type').text() === '')
                return true;
            return false;
        } // propertyBarIsEmpty

        /**
         * Shadow Properties that many display objects have
         */
        this.updateShadow = function(shadow) {
            $('#shadow-x').val(shadow.offsetX);
            $('#shadow-y').val(shadow.offsetY);
            $('#shadow-blur').val(shadow.blur);
            $('#shadow-colour').val(shadow.color.replaceAll('#', ''));
            $('#shadow-colour').css('backgroundColor', shadow.color);
            $('.shadow-heading').show();
        } // updateShadow

        /**
         * Position Properties for objects
         */
        this.updatePosition = function(obj) {
            $('#position-x').val(obj.x);
            $('#position-y').val(obj.y);
            $('#position-width').val(obj.width);
            $('#position-height').val(obj.height);
            $('.position-heading').show();
        } // updatePosition

        /**
         * Input, but with some subtypes
         */
        this.propertyAddInput = function(property, value, type) {
            //this.logger.debug('propertyAddInput::start');
            this.waitForUI(true);

            minMax = null;
            if (property === 'layer')
                minMax = " min='0' max='" + (designer.menuscene.widgets.length - 1) + "' ";

            $('.property-list').append(template.property.input({
                property: property,
                type: type,
                value: value,
                minMax: minMax
            }));

            this.activity.propertybar = false;
        } // propertyAddInput

        /**
         * Select, creates a select option
         */
        this.propertyAddSelect = function(property, selectedIndex, type, options) {
            //this.logger.debug('propertyAddSelect::start');
            this.waitForUI(true);

            $('.property-list').append(
                "<div class='md-property'><span class='text-capitalize'>" + property +
                "</span><select class='md-input' id='prop-" + property + "' type='" + type + "' /></div>");

            if (type === 'image') {
                I.getUrl('/image-gallery/files').then(function(response) {
                    files = JSON.parse(response);
                    if (files['files']) {
                        $.each(files['files'], function(key, imgFile) {
                            $('#prop-' + property).append($('<option>', {
                                value: imgFile['filelink']
                            }).text(imgFile['filename'].substring(0, imgFile['filename'].length - 4)));
                        });
                        $('#prop-' + property + " option[value='" + selectedIndex + "']").attr('selected', 'selected');
                    }
                }, function(error) {
                    console.error('Failed!', error);
                });
            } else {
                $.each(options, function(key, value) {
                    $('#prop-' + property).append($('<option>', {
                        value: key
                    }).text(value));
                });
                $('#prop-' + property + ' option[value=' + selectedIndex + ']').attr('selected', 'selected');
            }
            this.activity.propertybar = false;
        } // propertyAddSelect

        /**
         * Checkbox, tick hear if you understand
         */
        this.propertyAddCheckbox = function(property, isChecked) {
            //this.logger.debug('propertyAddCheckbox::start');
            this.waitForUI(true);

            checked = '';
            if (isChecked)
                checked = 'checked';

            $('.property-list').append(
                "<div class='md-property'><label for='prop-" + property + "' class='text-capitalize'>" + property +
                "</label><input class='md-input prop-" + property + " pull-right' id='prop-" + property + "' type='checkbox' " +
                checked + ' /></div>');
            this.activity.propertybar = false;
        } // propertyAddCheckbox

        /**
         * Properties bar Clicks
         */
        $('#property-apply').click(function() {
            //mbd.propertyEditor.logger.debug('#property-apply::click::start');

            if (mbd.property.type === 'text') {
                mbd.property.text = $('#prop-text').val();
                mbd.fontHelper.getFontArray(mbd.property.font);
                mbd.fontHelper.fontSize = $('#prop-size').val();
                mbd.fontHelper.fontFamily = $('#prop-font option:selected:selected').text();
                mbd.fontHelper.fontBold = $('#prop-bold').is(':checked') ? 'bold' : 'normal';
                mbd.fontHelper.fontItalic = $('#prop-italic').is(':checked') ? 'italic' : 'normal';
                mbd.property.fill = '#' + $('#prop-colour').val();
                mbd.property.font = mbd.fontHelper.getFontString();
            } else if (mbd.property.type === 'line') {
                var theStroke = [];
                theStroke.push($('#prop-size').val() + 'px');
                theStroke.push('#' + $('#prop-colour').val());
                mbd.property.stroke = theStroke.join(' ');
                mbd.property.cap = $('#prop-cap option:selected:selected').text();
            } // else if (mbd.property.type === 'image') {}

            mbd.property.x = $('#position-x').val() * 1;
            mbd.property.y = $('#position-y').val() * 1;

            mbd.property.rotation = parseInt($('#prop-rotation').val());

            var colour = $('#shadow-colour').val();
            if (colour !== 'transparent')
                colour = '#' + colour;

            mbd.property.shadow = $('#shadow-x').val() + ' ' + $('#shadow-y').val() + ' ' + $('#shadow-blur').val() + ' ' + colour;

            mbd.property.zIndex = parseInt($('#prop-layer').val()) + designer.gridscene.lines;
            mbd.property.redraw();

            designer.dirty = true;
            mbd.propertyEditor.trigger('apply', mbd.property);
            designer.layereditor.layersUpdate();
        });

        $('#property-delete').click(function() {
            //mbd.propertyEditor.logger.debug('#property-delete::click::start');
            if (mbd.property !== null)
                mbd.propertyEditor.propertyBarDestroy();
            designer.menuscene.removeWidget(mbd.property);
            mbd.propertyEditor.trigger('delete', mbd.property);
            designer.layereditor.layersUpdate();
            mbd.propertyEditor.selectWidget();
        });

        /**
         * Toolbar!
         */
        this.vInfoEdit = null;

        this.getModel = function(options) {
            if (mbd.data.model === null) {
                mbd.data.model = new Menu(options);
                // mbd.data.model.debug = true;
                this.listenTo(mbd.data.model, 'sync', this.saved);
            }
        } // getModel

        this.showInfo = function() {
            jsonCollection = designer.menuscene.serializeScene();

            mbd.propertyEditor.getModel();
            mbd.data.model.set('itemdata', jsonCollection);

            mbd.propertyEditor.vInfoEdit = new InfoView({
                el: $('.modal-body'),
                model: mbd.data.model
            });
            mbd.propertyEditor.vInfoEdit.render();

            $('#edit-info').modal('show');
        } // showInfo

        this.listenTo(mbd.toolbar, 'all', function(event, args) {
            if (args.group == 'file') {
                switch (args.type) {
                    case 'save':
                        mbd.propertyEditor.saveCanvas();
                        break;

                    default:
                        break;
                }
            } else if (args.group == 'objects') {
                if (args.type == 'image') {
                    mbd.propertyEditor.updateImageSelect();
                } else {
                    mbd.propertyEditor.addDisplayObject(args.type);
                }
            } else if (args.group == 'info') {
                mbd.propertyEditor.showInfo();
            }
        });

        /**
         * Display object stuff
         */
        this.handleMdWidgetClick = function() {
                //mbd.propertyEditor.logger.debug('handleMdWidgetClick::start');
                if (mbd.propertyEditor.propertyBarIsEmpty() || (this.id !== $('#property-type').data('id'))) {
                    //mbd.propertyEditor.logger.debug('handleMdWidgetClick::new::properties');
                    mbd.propertyEditor.selectWidget(this);
                    mbd.propertyEditor.propertyBarDestroy(function() {
                        mbd.propertyEditor.propertyBarCreate(mbd.property);
                    });
                }
            } // handleMdWidgetClick

        /**
         * Adds a border around widgets when they are selected
         */
        this.addWidgetOutline = function(widget) {
                var rectangle = designer.canvas.display.rectangle({
                    x: 0,
                    y: 0,
                    origin: {
                        x: 'left',
                        y: 'top'
                    },
                    width: widget.width,
                    height: widget.height,
                    stroke: 'outside 2px rgba(0, 0, 0, 0.5)'
                });

                widget.addChild(rectangle);
            } // addWidgetOutline

        /**
         * Un-selects any selected widget, selects new widget if
         * passed to function
         */
        this.selectWidget = function(widget) {
                if (mbd.property !== null && mbd.property !== undefined) {
                    if (mbd.property.children.length > 0) {
                        mbd.property.removeChildAt(0);
                        if (document.getElementById('tl' + mbd.property.id) !== null) {
                            document.getElementById('tl' + mbd.property.id).classList.remove('active');
                            $(document.getElementById('tl' + mbd.property.id)).children('i').addClass('hide');
                        }
                    }
                    mbd.property = null;
                }

                if (widget !== undefined) {
                    if (widget.hasOwnProperty('core')) {
                        mbd.propertyEditor.addWidgetOutline(widget);
                        document.getElementById('tl' + widget.id).classList.add('active');
                        $(document.getElementById('tl' + widget.id)).children('i').removeClass('hide');
                        mbd.property = widget;
                    }
                } else {
                	mbd.property = null;
                	mbd.propertyEditor.propertyBarDestroy();
                }
            } // selectWidget

        /**
         * Mouse
         */

        /**
         * Sets the mouse cursor based on which object its over
         */
        this.updateCursor = function(object) {
            designer.canvas.mouse.cursor(mbd.cursor.move);
            if (object.type == 'line') {
                var radius = (object.length / 4) ^ 2;

                var sS = mbd.propertyEditor.getDistance(object.parent.mouse, object.start);
                var eE = mbd.propertyEditor.getDistance(object.parent.mouse, object.end);

                var action;
                if (eE <= radius) {
                    action = 'start';
                } else if (sS >= (object.length - radius)) {
                    action = 'end';
                } else {
                    action = 'move';
                }

                if (action != 'move') {
                    if (mbd.shifted) {
                        designer.canvas.mouse.cursor(mbd.cursor.resize_x);
                    } else if (mbd.ctrled) {
                        designer.canvas.mouse.cursor(mbd.cursor.resize_y);
                    } else {
                        designer.canvas.mouse.cursor(mbd.cursor.resize);
                    }
                }
            }
        } // updateCursor

        /**
         * Event when mouse enters widget
         */
        this.handleMdWidgetMouseEnter = function(event) {
            mbd.propertyEditor.updateCursor(this);
        } // handleMdWidgetMouseEnter

        /**
         * Event when mouse moves over a widget
         */
        this.handleMdWidgetMouseMove = function(event) {
            mbd.propertyEditor.updateCursor(this);
        } // handleMdWidgetMouseMove

        /**
         * Event when mouse leaves widget
         */
        this.handleMdWidgetMouseLeave = function(event) {
            designer.canvas.mouse.cursor(mbd.cursor.normal);
        } // handleMdWidgetMouseLeave

        /**
         * Return distance between two x y points
         */
        this.getDistance = function(pointA, pointB) {
            var distance = ((pointA.x - pointB.x) ^ 2) + ((pointA.y - pointB.y) ^ 2);

            if (distance < 0)
                distance = mbd.propertyEditor.getDistance(pointB, pointA);

            return distance;
        } // getDistance

        /**
         * Adds drag and drop to a widget
         */
        this.dragAndDrop = function(object) {
            object.dragAndDrop({
            	bubble: false,
            	start: function() {
                    this.opacity = 0.5;
                    designer.canvas.mouse.cursor(mbd.cursor.move);
                    if (this.type == 'line') {
                        var radius = (this.length / 4) ^ 2;

                        var mousePoint = {
                            x: Math.round(this.parent.mouse.x),
                            y: Math.round(this.parent.mouse.y)
                        }

                        var sS = mbd.propertyEditor.getDistance(mousePoint, this.start);
                        var eE = mbd.propertyEditor.getDistance(mousePoint, this.end);

                        var action;
                        if (sS <= radius) {
                            action = 'start';
                        } else if (eE <= radius) {
                            action = 'end';
                        } else {
                            action = 'move';
                        }

                        if (action != 'move') {
                            if (mbd.shifted) {
                                designer.canvas.mouse.cursor(mbd.cursor.resize_x);
                            } else if (mbd.ctrled) {
                                designer.canvas.mouse.cursor(mbd.cursor.resize_y);
                            } else {
                                designer.canvas.mouse.cursor(mbd.cursor.resize);
                            }
                        }

                        mbd.line = {
                            action: action,
                            start: {
                                x: this.start.x,
                                y: this.start.y
                            },
                            end: {
                                x: this.end.x,
                                y: this.end.y
                            }
                        };
                    }
                },
                move: function() {
                    this.opacity = 0.2;
                    
                    // Check widget inside TOP and LEFT boundary 
                    this.x = this.x < 0 ? 0 : this.x;
                    this.y = this.y < 0 ? 0 : this.y;
                    
                    // Check widget inside BOTTOM and RIGHT boundary
                    this.x = this.x > (this.parent.width - this.width) ? (this.parent.width - this.width) : this.x;
                    this.y = this.y > (this.parent.height - this.height) ? (this.parent.height - this.height) : this.y;

                    if (this.type == 'line') {
                        if (mbd.line.action != 'move') {
                            var pointStatic, pointStaticValues;
                            var pointMove, pointMoveValues;

                            if (mbd.line.action == 'start') {
                                pointStatic = this.end;
                                pointStaticValues = mbd.line.end;
                                pointMove = this.start;
                                pointMoveValues = mbd.line.start;
                            } else if (mbd.line.action == 'end') {
                                pointStatic = this.start;
                                pointStaticValues = mbd.line.start;
                                pointMove = this.end;
                                pointMoveValues = mbd.line.end;
                            }

                            if (mbd.shifted) {
                                pointMove.x = pointMoveValues.x;
                                pointMove.y = Math.round(designer.canvas.mouse.y / designer.gridscene.snapTo) * designer.gridscene.snapTo;
                            } else if (mbd.ctrled) {
                                pointMove.y = pointMoveValues.y;
                                pointMove.x = Math.round(designer.canvas.mouse.x / designer.gridscene.snapTo) * designer.gridscene.snapTo;
                            } else {
                                pointMove.x = Math.round(designer.canvas.mouse.x / designer.gridscene.snapTo) * designer.gridscene.snapTo;
                                pointMove.y = Math.round(designer.canvas.mouse.y / designer.gridscene.snapTo) * designer.gridscene.snapTo;
                            }

                            pointStatic.x = pointStaticValues.x;
                            pointStatic.y = pointStaticValues.y;
                        }
                    }

                    $('#position-x').val(Math.round(this.x));
                    $('#position-y').val(Math.round(this.y));
                },
                end: function() {
                    var type = this.type;
                    if (type !== 'line') {
                        // If snapping ON calculate the final x,y
                        if (document.getElementById('gridEnabled').checked) {
                        	// SnapTo closest grid lines
                            this.x = Math.round(this.x / designer.gridscene.snapTo) * designer.gridscene.snapTo;
                            this.y = Math.round(this.y / designer.gridscene.snapTo) * designer.gridscene.snapTo;
                            
                            // If SnapTo crosses BOTTOM or RIGHT boundary reduce by one SnapTo step
                            this.x = (this.x + this.width) > this.parent.width ? this.x - designer.gridscene.snapTo : this.x;
                            this.y = (this.y + this.height) > this.parent.height ? this.y - designer.gridscene.snapTo : this.y;
                        }
                    }
                    //mbd.propertyEditor.logger.debug('x:' + this.x + ', y: ' + this.y);
                    this.opacity = 1;
                    designer.canvas.mouse.cursor(mbd.cursor.normal);

                    if (mbd.property && this.id == mbd.property.id)
                        if ($('#position-x').val() != this.x && $('#position-y').val() != this.y)
                            designer.dirty = true;

                    $('#position-x').val(this.x);
                    $('#position-y').val(this.y);

                    this.trigger('move', this);
                }
            });
        } // dragAndDrop

        /**
         * Creating Widgets
         */

        /**
         * Update image picket for image widget
         */
        this.updateImageSelect = function() {
            I.getUrl('/image-gallery/files').then(function(response) {
                files = JSON.parse(response);
                if (files.files) {
                    document.getElementById('image-picker').innerHTML = '';
                    $.each(files.files, function(key, imgFile) {
                        if (imgFile['filename'].split('.').pop() !== 'wmv') {
                            $('#image-picker').append($('<option>', {
                                value: imgFile.filelink,
                                'data-img-src': imgFile.filelink,
                                'data-img-label': imgFile.filename + ' (' + imgFile.width + 'x' + imgFile.height +
                                    ')'
                            }).text(imgFile.filename.substring(0, imgFile.filename.length - 4)));
                        }
                    });

                    $('#image-picker-modal').fadeIn(1000);

                    $('#image-picker').imagepicker({
                        hide_select: false,
                        show_label: true,
                        selected: function(e) {
                            $('#image-picker-modal').fadeOut(1000);
                            mbd.propertyEditor.addDisplayObject('image', e.option.data('img-src'));
                        }
                    });
                }
            }, function(error) {
                console.error('Failed!', error);
            });
        } // updateImageSelect

        /**
         * New widget wrapper for common properties
         */
        this.addDisplayObject = function(type, value, origin) {
            x = designer.gridscene.snapTo;
            y = designer.gridscene.snapTo;
            newX = 0;

            if (typeof origin === 'undefined') {
                for (i = 0; i < designer.menuscene.widgets.length; i++) {
                    var child = designer.menuscene.widgets[i];
                    if (!child.pointerEvents)
                        continue;

                    if (newX === 0)
                        newX = designer.gridscene.nextGridLine(child.x + child.width);
                    if (y == child.y) {
                        y = designer.gridscene.nextGridLine(y + child.height);
                        if (y >= (designer.canvas.height - designer.gridscene.snapTo)) {
                            y = designer.gridscene.snapTo;
                            x = Math.round(newX / designer.gridscene.snapTo) * designer.gridscene.snapTo;
                            newX = 0;
                        }
                    }
                }
            }

            var data = {
                type: type,
                x: x,
                y: y,
                origin: {
                    x: 'left',
                    y: 'top'
                }
            };

            switch (type) {
                case 'text':
                    textString = value || 'Object field ' + (designer.menuscene.widgets.length + 1);
                    data.font = mbd.defautls.font.font;
                    data.text = window.prompt('Text:', textString);
                    data.fill = mbd.defautls.font.colour;
                    if (!data.text) data = null;
                    break;

                case 'line':
                    data.start = {
                        x: x,
                        y: y
                    };
                    data.end = {
                        x: x + 200,
                        y: y
                    };
                    data.stroke = '30px #000';
                    data.cap = 'butt';
                    break;

                case 'image':
                    data.image = value;
                    break;

                default:
                    designer.notice('Still working on object ' + type, 'warning');
                    data = null;
                    break;
            }

            if (data !== null) {
                designer.menuscene.addWidget(data);
                designer.layereditor.layersUpdate();
            }
        } // addDisplayObject

        /**
         * Loading and Saving
         */
        this.saved = function(model, attributes, request) {
            // console.log(this);
            // console.log(model);
            // console.log(attributes);
            // console.log(request);
        } // saved

        /**
         * Loads a menu by id
         */
        this.loadScreen = function(id) {
            mbd.propertyEditor.getModel({
                id: id
            });
            //this.listenTo(mbd.data.model, 'sync', this.saved);
            mbd.data.model.fetch({
                success: function(model, response, options) {
                	if (document.getElementById('gridSnap').value != model.get('grid')) {
                		document.getElementById('gridSnap').value = model.get('grid');
                		designer.gridscene._createGrid({
	                        snapTo: model.get('grid') * 1
	                    });
                	}
                	
                	jsonData = model.get('itemdata');
                    background = model.get('background');
                    fk_clients = model.get('fk_clients');
                    
                    if (background) {
                        designer.layout.backgroundVideo = background;
                    }
                    collection = JSON.parse(jsonData);

                    for (i = 0; i < collection.length; i++) {
                        var obj = collection[i];
                        designer.menuscene.addWidget(obj);
                    }
                    designer.layereditor.layersUpdate();
                    designer.notice('Loaded ' + mbd.data.model.get('name'), 'success');
                    designer.dirty = false;
                }
            });
        } // loadScreen

        /**
         * Saves the canvas
         */
        this.saveCanvas = function() {
        	mbd.toolbar.vSave.el.getElementsByTagName('img')[0].classList.add('flash');
            mbd.propertyEditor.selectWidget();
            jsonCollection = designer.menuscene.serializeScene();

            mbd.propertyEditor.getModel();

            curData = mbd.data.model.get('itemdata');

            if ((curData === jsonCollection) && (designer.dirty === false)) {
                designer.notice('No modifications to save.', 'info');
            } else {
                designer.notice('Saving...', 'info');
                mbd.data.model.set('itemdata', jsonCollection);
                
                mbd.data.model.set('grid', designer.gridscene.snapTo);

                // SAVE PREVIEW
                var ratio = 0.2;
                var cv = document.getElementById('canvas').cloneNode(true);
                var cc = cv.getContext('2d');
                cc.canvas.height = cc.canvas.height * ratio;
                cc.canvas.width = cc.canvas.width * ratio;
                cc.drawImage(document.getElementById('canvas'), 0, 0, cc.canvas.width, cc.canvas.height);

                mbd.data.model.set('preview', cv.toDataURL('image/png'));

                mbd.data.model.save(mbd.data.model.attributes, {
                    success: function(model, response, options) {
                    	mbd.logger.debug('Model Save Success');
                        mbd.propertyEditor.trigger('saved', model);
                        designer.notice('Saved', 'success');
                        designer.dirty = false;
                        mbd.toolbar.vSave.el.getElementsByTagName('img')[0].classList.remove('flash');
                    },
                    error: function(model, response) {
                    	mbd.logger.error('Model Save Fail', response);
                    	mbd.logger.error(model);
                    	mbd.toolbar.vSave.el.getElementsByTagName('img')[0].classList.remove('flash');
                    }
                });
            }
            //mbd.toolbar.vSave.el.getElementsByTagName('img')[0].classList.remove('flash');
        } // saveCanvas

        designer.on('dirty', function(e) {
            mbd.toolbar.vSave.setState(e.dirty);
        });

        $('[data-toggle="tooltip"]').tooltip();
    } // PropertyEditor
    
    mbd.propertyEditor = new PropertyEditor();
    
    /**
     * Short-Cuts
     * 
     * alt+shift+s  : Save
     * 
     * ctrl+shift+g : Grid Lines
     * ctrl+shift+s : SnapTo
     * 
     * ctrl+shift+t : text
     * ctrl+shift+l : line
     * ctrl+shift+i : image
     * 
     * alt+shift+up : Zoom In
     * alt+shift+down : Zoom Out
     */
    
    shortcut.add('alt+shift+s', function() {
        mbd.propertyEditor.saveCanvas();
    });
    
    shortcut.add('ctrl+shift+g', function() {
    	designer.gridscene.visible = document.getElementById('gridLines').checked = !document.getElementById('gridLines').checked;
    });
    
    shortcut.add('ctrl+shift+s', function() {
    	document.getElementById('gridEnabled').checked = !document.getElementById('gridEnabled').checked;
    });
    
    shortcut.add('ctrl+shift+t', function() {
    	mbd.propertyEditor.addDisplayObject('text');
    });
    
    shortcut.add('ctrl+shift+l', function() {
    	mbd.propertyEditor.addDisplayObject('line');
    });

	shortcut.add('ctrl+shift+i', function() {
		mbd.propertyEditor.updateImageSelect();
	});
	
	shortcut.add('alt+shift+up', function() {
		document.getElementById('canvasZoom').value = (document.getElementById('canvasZoom').value * 1) + (document.getElementById('canvasZoom').step * 1);
		document.getElementById('canvasZoom').dispatchEvent(new Event('change'));
	});
	
	shortcut.add('alt+shift+down', function() {
		document.getElementById('canvasZoom').value -= document.getElementById('canvasZoom').step;
		document.getElementById('canvasZoom').dispatchEvent(new Event('change'));
	});

    if ($('#canvas').data('id') !== '') {
        mbd.propertyEditor.loadScreen($('#canvas').data('id'));
    } else {
        mbd.propertyEditor.showInfo();
    }

    $(window).bind('beforeunload', function() {
        if (designer.dirty) {
            return 'You have unsaved Menu changes. Do you want to leave this Menu and discard your changes or stay on this page?';
        }
    });
}, this);
