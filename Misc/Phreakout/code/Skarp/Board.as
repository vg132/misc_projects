#include "Obstacle.as"
#include "Paddle.as"
#include "Prize.as"
#include "Ring.as"
#include "Fence.as"

// Konstruktor för Board
_global.Board = function()
{
	// Skapa tomma ringar att placera klossar i
	this.rings = new Array();
	this.rings.push(new Ring(49, 1));
	this.rings.push(new Ring(74, -2));
	this.rings.push(new Ring(100, 2));
	this.rings.push(new Ring(125, -1));

	// Skapa klossar
	for(var i=0; i<8; i++) // Innersta ringen
		this.rings[0].addObstacle(new Obstacle(0, 0, i*45, 1, 1, this.rings[0].getClip()));
	for(var i=0; i<12; i++) // Andra ringen
		this.rings[1].addObstacle(new Obstacle(0, 0, i*30, 2, -2, this.rings[1].getClip()));
	for(var i=0; i<16; i++) // Tredje ringen
		this.rings[2].addObstacle(new Obstacle(0, 0, i*22.5, 3, 2, this.rings[2].getClip()));
	for(var i=0; i<20; i++) // Yttersta ringen
		this.rings[3].addObstacle(new Obstacle(0, 0, i*18, 4, -1, this.rings[3].getClip()));

	// Skapa en mitt-kloss.
	this.prize = new Prize(Xc, Yc);

	// Skapa kantklossar
	this.edgeLeft = 1000;
	this.edgeTop = 1000;
	this.edgeRight = 0;
	this.edgeBottom = 0;

	this.fenceRows = 19; // Antal rader
	this.fenceCols = 19; // Antal kolumner
	this.fence_x0 = 15; // X för översta vänstra klossen
	this.fence_y0 = 15; // Y för översta vänstra klossen
	this.fences = new Array();
	var i;
	for(i=0; i<this.fenceRows; i++)
		this.addFence(0, i);
	for(i=0; i<this.fenceRows; i++)
		this.addFence(this.fenceCols-1, i);
	for(i=1; i<this.fenceCols-1; i++)
		this.addFence(i, 0);
	for(i=1; i<this.fenceCols-1; i++)
		this.addFence(i, this.fenceCols-1);

	this.edgeLeft+=Fence.width-1;
	this.edgeTop+=Fence.width-1;
}

// Lägger till en kantkloss vid col, row
Board.prototype.addFence = function(col, row)
{
	var x = this.fence_x0+col*Fence.width;
	var y = this.fence_y0+row*Fence.width;

	if(x<this.edgeLeft) this.edgeLeft=x;
	if(y<this.edgeTop) this.edgeTop = y;
	if(x>this.edgeRight) this.edgeRight=x;
	if(y>this.edgeBottom) this.edgeBottom=y;

	this.fences[row*this.fenceCols + col] = new Fence(x, y);
}

// Returnerar en kantkloss vid punkten (x, y). Om ingen kloss finns där, returneras undefined.
Board.prototype.getFence = function(x, y)
{	
	var col = Math.floor((x-this.fence_x0)/Fence.width);
	var row = Math.floor((y-this.fence_y0)/Fence.width);
	if(col<0 || row<0 || col>=this.fenceCols || row>=this.fenceRows)
		return;
	return this.fences[row*this.fenceCols + col];
}

// Återställer spelplanen till utgångsläget
Board.prototype.reset = function()
{
	for(var r=0; !hit && r<this.rings.length; r++)
	{
		this.rings[r].angle = 0;
		for(var j=0; !hit && j<this.rings[r].obstacles.length; j++)
			this.rings[r].obstacles[j].setVisible(true);
	}

	for(var i=0; i<this.fences.length; i++)
		if(this.fences[i]!=undefined)
			this.fences[i].setVisible(true);

	this.prize.setVisible(true);
}
