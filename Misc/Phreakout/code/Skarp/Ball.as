Ball.instanceCount = 0; // Antal bollar som skapats

// Konstruktor för Ball
// Parametrar:
// x, y: Startposition
// dx, dy: Riktning/hastighet
_global.Ball = function(x, y, dx, dy)
{	
	// Skapa ett MovieClip
	var iobj = {_x:x, _y:y, _name:"ball" add (++Ball.instanceCount)};
	attachMovie("Ball", iobj._name, (++_global.curDepth), iobj);
	this.clip = _root[iobj._name];
	this.clip._width=8;
	this.clip._height=8;
	this.speedScale = 0;
	this.acc = .025;

	// Medlemsvariabler
	this.x = x;
	this.y = y;
	this.dx = dx;
	this.dy = dy;
	this.ix = 0;  //tillägg av Gunnar, impact-koordinaterna
	this.iy = 0;
	this.delay = 0;
}


// Get/Set-metoder
Ball.prototype.getX  = function(){ return this.x; }
Ball.prototype.setX  = function(x){ this.x = x; }
Ball.prototype.getY  = function(){ return this.y; }
Ball.prototype.setY  = function(y){ this.y = y; }
Ball.prototype.getDX = function(){ return this.dx;}
Ball.prototype.setDX = function(dx){ this.dx = dx;}
Ball.prototype.getDY = function(){ return this.dy;}
Ball.prototype.setDY = function(dy){ this.dy = dy;}
Ball.prototype.getIX = function(){return this.ix;}
Ball.prototype.getIY = function(){return this.iy;}


// Flyttar bollen
Ball.prototype.move = function()
{
	if(this.delay>0)
	{
		this.delay--;
		return;
	}
	if(this.speedScale<1)
	{
		if((this.speedScale+=this.acc)>1.0) this.speedScale=1.0;
	}
	this.x+= this.dx*this.speedScale;
	this.y+= this.dy*this.speedScale;
}

// Uppdaterar bollens MovieClip-position på skärmen
Ball.prototype.draw = function()
{
	this.clip._x = this.x;
	this.clip._y = this.y;
}

// Placerar bollen på slumpmässig position med avståndet dist från centrum, 
// och sätter hastigheten till speed i slumpmässig riktning.
Ball.prototype.randomPos = function(dist, speed)
{
	// Slumpa fram bollens position
	var v = Math.random()*Math.PI*2.0;
	this.x = Math.cos(v)*dist+Xc;
	this.y = Math.sin(v)*dist+Yc;

//	Slumpa fram bollens riktning
//	var v = Math.random()*Math.PI*2.0;
//	this.dx = Math.cos(v)*speed;
//	this.dy = Math.sin(v)*speed;

	// Rikta bollen rakt utåt
	this.dx = this.x-Xc;
	this.dy = this.y-Yc;
	l = Math.sqrt(this.dx*this.dx + this.dy*this.dy);
	this.dx= this.dx*speed/l;
	this.dy= this.dy*speed/l;

	this.delay = 30;
	this.speedScale = 0.0;

	this.turn(Math.random()*60-30);
}

// Roterar bollens riktning ett visst antal grader
Ball.prototype.turn = function(angle)
{
	var cos = Math.cos(angle*Math.PI/180.0);
	var sin = Math.sin(angle*Math.PI/180.0);
	var dx = this.dx*cos - this.dy*sin;
	var dy = this.dx*sin + this.dy*cos;
	this.dx = dx;
	this.dy = dy;
}

// Returnerar bollens riktning i grader
Ball.prototype.getDirection = function()
{
	return Math.atan(this.dy/this.dx)*180.0/Math.PI;
}

// Returnerar bollens hastighet
Ball.prototype.getSpeed = function()
{
	return this.speedScale*Math.sqrt(this.dx*this.dx + this.dy*this.dy);
}

