/*
 * GROUP LAYER
 */
var rectangle = designer.canvas.display.rectangle({
	x: 0,
	y: 0,
	origin: {
		x: 'left',
		y: 'top'
	},
	width: 500,
	height: 500,
	stroke: 'outside 2px rgba(0, 0, 0, 0.5)'
});
mbd.propertyEditor.dragAndDrop(rectangle);
designer.menuscene.canvas.addChild(rectangle);



var rect = designer.canvas.display.rectangle({
	x: 0,
	y: 0,
	origin: {
		x: 'left',
		y: 'top'
	},
	width: 50,
	height: 50,
	stroke: 'outside 2px rgba(0, 0, 0, 0.5)'
});
mbd.propertyEditor.dragAndDrop(rect);
designer.menuscene.canvas.children[0].addChild(rect);
