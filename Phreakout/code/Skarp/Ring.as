Ring.instanceCount = 0;

// Konstruktor f�r Ring
// Klassen inneh�ller ett tomt MovieClip som fungerar som parent �t Obstacle-instanser.
_global.Ring = function(radius, speed)
{
	this.obstacles = new Array();
	this.radius = radius;
	this.angle = 0;
	this.speed = speed;
	this.clip = _root.createEmptyMovieClip ("ring" add (++Ring.instanceCount), (++_global.curDepth));

	this.clip._x = Xc;
	this.clip._y = Yc;
}

// Testa om (x, y) ligger innanf�r ringens radie
Ring.prototype.hitTest = function(x, y)
{
	var dx = x-Xc;
	var dy = y-Yc;
	return Math.sqrt(dx*dx + dy*dy) <= this.radius;
}

// L�gg till en Obstacle-instans i ringen
Ring.prototype.addObstacle = function(obstacle)
{
	this.obstacles.push(obstacle);
}

// Ret. en referens till ringens MovieClip
Ring.prototype.getClip = function()
{
	return this.clip;
}

// Snurraringen och dess Obstacle-instanser
Ring.prototype.move = function()
{
	this.angle+= this.speed;
}

// Uppdaterar objektets MovieClip-position p� sk�rmen
Ring.prototype.draw = function()
{
	this.clip._rotation = this.angle;
}
