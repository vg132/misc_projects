// GLOBALT KRAFS
// Ska flyttas till ... någon annanstans. Client, förslagsvis.

// Spelplanens mitt
_global.Xc = 300; 
_global.Yc = 300;
// Håller reda på aktuell Z-order för MovieClips. Bör användas av all grafik som skapas i runtime.
_global.curDepth = 1000; 

// Returnerar skalärprodukten mellan vektorerna (x1,y1) och (x2,y2).
_global.dotPrd = function(x1, y1, x2, y2)
{
	return x1*x2 + y1*y2;
}

// Returnerar skärningspunkten mellan linjerna (x1,y1)-(x2,y2) och (x3,y3)-(x4,y4).
_global.intersect = function(x1, y1, x2, y2, x3, y3, x4, y4)
{
	var ua = ((x4-x3)*(y1-y3) - (y4-y3)*(x1-x3)) / ((y4-y3)*(x2-x1) - (x4-x3)*(y2-y1));
	return {x:(x1 + ua*(x2-x1)), y:(y1 + ua*(y2-y1))};
}


// Konstruktor för BoardObject
// Parametrar:
// centerx, centery: Den punkt som objektet roterar kring
// angle: Vinkel vid start
// instanceName: Namn på objektets MovieClip
// clipName: Klassnamn på MovieClip
// speed: Ursprunglig hastighet
// width: Bredd, i grader, på objektet
// maxSpeed: Max hastighet
// parent: Omslutande MovieClip
_global.BoardObject = function(centerx, centery, angle, instanceName, clipName, speed, 
	width, maxSpeed, parent)
{
	if(parent == undefined) parent = _root;

	// Skapa ett MovieClip
	var iobj = new Object();
	iobj._x = centerx;
	iobj._y = centery;
	iobj._rotation = angle;
	parent.attachMovie(clipName, instanceName, (++_global.curDepth), iobj);
	delete iobj;

	// Medlemsvariabler
	this.clip = parent[instanceName];
	this.angle = angle;
	this.angle0 = angle;
	this.speed = speed;
	this.width = width;
	this.maxSpeed = maxSpeed;


}

// Återställer klossen till dess ursprungliga position
Obstacle.prototype.reset = function()
{
	this.angle = this.angle0;
	draw();
	setVisible(true);
}

// Uppdaterar objektets MovieClip-position på skärmen
BoardObject.prototype.draw = function()
{
	this.clip._rotation = this.angle;
}

// Uppdaterar objektets vinkel enligt aktuell hastighet
BoardObject.prototype.move = function()
{
	this.angle+= this.speed;
	while(this.angle<0)this.angle+=360;
	//this.angle = (this.angle+this.speed)%360;
}

BoardObject.prototype.getAngle = function()
{
	return this.angle;
}

BoardObject.prototype.setAngle = function(angle)
{
	this.angle = angle;
	while(this.angle<0)this.angle+=360;
}

// Döljer/visar objektets MovieClip
BoardObject.prototype.setVisible = function(visible)
{
	this.clip._visible = visible;
}

// Ret true on objektets MovieClip är synligt
BoardObject.prototype.getVisible = function()
{
	return this.clip._visible;
}

// Ret en referens till objektets MovieClip
BoardObject.prototype.getClip = function()
{
	return this.clip;
}

// Accelererar/deccelererar objektet
BoardObject.prototype.addSpeed = function(acc)
{
	this.setSpeed(this.speed+acc);
}

// Returnerar aktuell hastighet
BoardObject.prototype.getSpeed = function()
{
	return this.speed;
}

// Returnerar max hastighet
BoardObject.prototype.getMaxSpeed = function()
{
	return this.maxSpeed;
}

// Sätter aktuell hastighet
BoardObject.prototype.setSpeed = function(speed)
{
	this.speed = speed;
	if(this.speed>this.maxSpeed) this.speed=this.maxSpeed; else
	if(this.speed<-this.maxSpeed) this.speed=-this.maxSpeed; 
}

// Testar kollision mellan en boll och detta BoardObject.
// Om kollision inträffar, ändras bollens riktning och 
// true returneras, annars returneras bara false. 
// Funkar nästan, oftast, mer eller mindre;-). Problemet 
// är att avgöra när bollen träffar kortsidan. Bättre idéer 
// mottages tacksamt.
BoardObject.prototype.bounce = function(ball)
{
	var v, nx, ny, dx, dy, l, dot, p;
	// Om bollen ligger på objektet...
	if(this.clip._visible && this.clip.hit.hitTest(ball.x, ball.y, true))
	{
		// Beräkna normalvektorn (nx, ny) för klossens långsida. 
		nx = ball.x-Xc;
		ny = ball.y-Yc;
		l = Math.sqrt(nx*nx + ny*ny);
		nx/=l;
		ny/=l;
		
		// Avgör om bollen har träffat kortsidan
		var edge = false;
		v = this.angle * Math.PI/180.0;
		p = intersect(Xc, Yc, Xc+Math.cos(v), Yc+Math.sin(v),
			ball.x, ball.y, ball._x-ball.dx, ball._y-ball.dy);
		if(this.clip.hit.hitTest(p.x, p.y, true))
			edge = true;
		else
		{
			v = (this.clip.angle-this.width) * Math.PI/180.0;
			p = intersect(Xc, Yc, Xc+Math.cos(v), Yc+Math.sin(v),
				ball.x, ball.y, ball.x-ball.dx, ball.y-ball.dy);
			edge = this.clip.hit.hitTest(p.x, p.y, true);
		}
	
		// Beräkna ny riktning
		dot = dotPrd(nx, ny, ball.dx, ball.dy);
		if(edge)
		{
			// Studsa mot kortsidan
			ball.dx = 2 * nx * dot - ball.dx;
			ball.dy = 2 * ny * dot - ball.dy;
		}
		else
		{
			// Studsa mot långsidan
			ball.dx = -2 * nx * dot + ball.dx;
			ball.dy = -2 * ny * dot + ball.dy;
		}
		return true;
	}
	return false;
}
