Prize.instanceCount = 0; // Antal instanser

// Konstruktor f�r Prize
// Parametrar:
// centerx, centery: Mittpunkt
_global.Prize = function(centerx, centery)
{
	var iobj = {_x:centerx, _y:centery, _rotation:0, angle:0, _name:"mid" add (++Prize.instanceCount)};
	attachMovie("Obstacle0", iobj._name, (++_global.curDepth), iobj);
	this.clip = _root[iobj._name];
	delete iobj;
}

// D�ljer/visar objektets MovieClip
Prize.prototype.setVisible = function(visible)
{
	this.clip._visible = visible;
}

// Ret true on objektets MovieClip �r synligt
Prize.prototype.getVisible = function()
{
	return this.clip._visible;
}

// Testar kollision mellan en boll och detta BoardObject.
// Om kollision intr�ffar, �ndras bollens riktning och 
// true returneras, annars returneras bara false.
Prize.prototype.bounce = function(ball)
{
	var nx, ny, dx, dy, l, dot;
	// Om bollen ligger p� objektet...
	if(this.clip._visible && this.clip.hitTest(ball.x, ball.y, true))
	{
		// Ber�kna normalvektorn (nx, ny) f�r klossen. 
		nx = ball.x-Xc;
		ny = ball.y-Yc;
		l = Math.sqrt(nx*nx + ny*ny);
		nx/=l;
		ny/=l;

		// Studsa bollen
		dot = dotPrd(nx, ny, ball.dx, ball.dy);
		ball.dx = -2 * nx * dot + ball.dx;
		ball.dy = -2 * ny * dot + ball.dy;

		return true;
	}
	return false;
}
