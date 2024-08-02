Fence.instanceCount = 0; // Antal instanser
Fence.width = 0;
Fence.maxLives = 0;

// Konstruktor f�r Fence
// Parametrar:
// x, y: Plats f�r �vre v�nstra h�rnet
// parent: parent-MovieClip
_global.Fence = function(x, y, parent)
{
	Fence.width = 30;
	Fence.maxLives = 3;

	if(parent == undefined) parent = _root;
	// Skapa ett MovieClip
	var iobj = new Object();
	iobj._x = x;
	iobj._y = y;
	iobj._rotation = angle;
	iobj._name = "square" add (++Fence.instanceCount);
	parent.attachMovie("Square", iobj._name, (++_global.curDepth), iobj);
	this.clip = parent[iobj._name];
	this.lives = Fence.maxLives;
	delete iobj;

}

Fence.prototype.getX = function()
{
	return this.clip._x;
}

Fence.prototype.getY = function()
{
	return this.clip._y;
}

// Testa om (x, y) ligger innanf�r klossen
Fence.prototype.hitTest = function(x, y)
{
	return false;
}

// Studsa boll mot klossen. F�ruts�tter att bollen ligger p� klossen.
// Ret true om klossen har tr�ffats s� m�nga g�nger att den ska tas bort
Fence.prototype.bounce = function(ball)
{
	var minx = ball.x - this.clip._x;
	var miny = ball.y - this.clip._y;
	if(minx>Fence.width/2) minx = Fence.width - minx;
	if(miny>Fence.width/2) miny = Fence.width - miny;
	
	if(minx<miny)
		ball.dx = -ball.dx;
	else	
		ball.dy = -ball.dy;

	this.clip._alpha = 100*((--this.lives) / Fence.maxLives);
	return(this.lives<=0);
}

// D�ljer/visar objektets MovieClip
Fence.prototype.setVisible = function(visible)
{
	this.clip._visible = visible;
	if(visible)
	{
		this.lives = Fence.maxLives;
		this.clip._alpha = 100;
	}
}

// Ret true on objektets MovieClip �r synligt
Fence.prototype.getVisible = function()
{
	return this.clip._visible;
}
