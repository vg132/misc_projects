//----------------BALL.AS----------------
Ball.instanceCount = 0; // Antal bollar som skapats

// Konstruktor f�r Ball
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

	// Medlemsvariabler
	this.x = x;
	this.y = y;
	this.dx = dx;
	this.dy = dy;
}

// Flyttar bollen
Ball.prototype.move = function()
{
	this.x+= this.dx;
	this.y+= this.dy;
}

// Uppdaterar bollens MovieClip-position p� sk�rmen
Ball.prototype.draw = function()
{
	this.clip._x = this.x;
	this.clip._y = this.y;
}

// Returnerar bollens riktning i grader
Ball.prototype.getDirection = function()
{
	return Math.atan(this.dy/this.dx)*180.0/Math.PI;
}

// Returnerar bollens hastighet
Ball.prototype.getSpeed = function()
{
	return Math.sqrt(this.dx*this.dx + this.dy*this.dy);
}
//----------------BALL.AS----------------
//----------------Prize.as---------------
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
//--------------prize.as----------------
Paddle.instanceCount = 0; // Antal paddlar som har skapats

_global.Paddle = function(centerx, centery, angle, playerName)
{
	// Anropa superklassens konstruktor
	super(centerx, centery, angle, "paddle" + (++this.instanceCount), "Paddle", 0, 25, 4);

	// Medlemsvariabler
	this.playerName = playerName;
	this.score = 0;
}
Paddle.prototype = new BoardObject();

// L�gger till po�ng
Paddle.prototype.addScore = function(score)
{
	this.score+= score;
}

// S�tter spelarens po�ng
Paddle.prototype.setScore = function(score)
{
	this.score = score;
}

// Returnerar spelarens po�ng
Paddle.prototype.getScore = function()
{
	return this.score;
}

// Ret spelarens namn
Paddle.prototype.getPlayerName = function()
{
	return this.playerName;
}
//----------------Paddle------------------
Obstacle.instanceCount = 0; // Antal instanser
Obstacle.widths = new Array(0, 45, 30, 22.5, 18); // Bredder f�r de olika niv�erna av klossar

// Konstruktor f�r Obstacle
// Parametrar:
// centerx, centery: Den punkt som objektet roterar kring
// angle: Vinkel vid start
// level: Ring-niv� (1=innersta ringen n�rmast center-klossen)
// speed: Hastighet
_global.Obstacle = function(centerx, centery, angle, level, speed)
{
	// Anropa superklassens konstruktor
	super(centerx, centery, angle, "obst" add (++Obstacle.instanceCount), "Obstacle" add level, 
		speed, widths[level], 100);

	// Medlemsvariabler
	this.level = level;
}

Obstacle.prototype = new BoardObject();

//------------------MessageHandler.as-------------------------

//Local variable
var myURL;
var myPort;
var mySocket;

//Function hooks
var onHostlist;
var onPlayerlist;
var onLogin;
var onDefault;
var onChat;

function MessageHandler(url,port)
{
	this.myURL=url;
	this.myPort=port;
	this.onHostlist=null;
	this.onPlayerlist=null;
	this.onLogin=null;
	this.onDefault=null;
	this.mySocket=new XMLSocket();
	this.mySocket._this=this;
	this.mySocket.onData=this.myOnData;
}

MessageHandler.prototype.getSocket=function()
{
	return(mySocket);
}

MessageHandler.prototype.myOnData=function(src)
{
	if(src.substr(0,5)=="LOGIN")
	{
		this._this.onLogin(src.substr(6));
	}
	else if(src.substr(0,8)=="HOSTLIST")
	{
		this._this.onHostlist(src.substr(9));
	}
	else if(src.substr(0,10)=="PLAYERLIST")
	{
		this._this.onPlayerlist(src.substr(11));
	}
	else if(src.substr(0,4)=="CHAT")
	{
		this._this.onChat(src.substr(5));
	}
	else
	{
		this._this.onDefault(src);
	}
}

MessageHandler.prototype.connect=function()
{
	this.mySocket.connect(this.myURL,this.myPort);
}

MessageHandler.prototype.login=function(username)
{
	this.sendData("CONNECT|"+username);
}

MessageHandler.prototype.sendData=function(data)
{
	this.mySocket.send(data+"\n");
}

MessageHandler.prototype.closeConnection=function()
{
	this.sendData("QCONNECT");
}

MessageHandler.prototype.host=function(gameName,players)
{
	this.sendData("HOST|"+gameName+"|"+players);
}

MessageHandler.prototype.cancelHost=function()
{
	this.sendData("QHOST");
}

MessageHandler.prototype.join=function(gameName)
{
	this.sendData("JOIN|"+gameName);
}

MessageHandler.prototype.cancelGame=function()
{
	this.sendData("QJOIN");
}

MessageHandler.prototype.getHostlist=function()
{
	this.sendData("HOSTLIST");
}

MessageHandler.prototype.chat=function(message)
{
	this.sendData("CHAT|"+message);
}
//------------------------------------------------------------

// Konstruktor f�r Game
function Game()
{
}

_global.Game = function()
{
	// Skapa en paddel-array
	this.paddles = new Array();

	// Skapa en boll
	this.balls = new Array();
	this.balls.push(new Ball(500, 240, -6, 1));

	// Skapa ett br�de
	this.board = new Board();
}

// L�gg till en spelare, allts� en paddel
Game.prototype.addPlayer = function(name, angle, localPlayer)
{
	var p = new Paddle(Xc, Yc, angle, name);
	this.paddles.push(p);
	if(localPlayer)
		this.myPaddle = p;
}

// Ny iter av gameloopen
Game.prototype.gameLoop = function()
{
	this.handleKeys();
	this.moveObjects();
	this.testCollisions();
	this.drawObjects();
}

// Flyttar alla objekt
Game.prototype.moveObjects = function()
{
	var i=0;
	// Flytta paddlarna
	for(i=0; i<this.paddles.length; i++)
		this.paddles[i].move(); 

	// Flytta bollarna
	for(i=0; i<this.balls.length; i++)
		this.balls[i].move();

	// Flytta klossarna	
	for(i=0; i<this.board.obstacles.length; i++)
		this.board.obstacles[i].move();
}

// Hanterar knapptryckningar
Game.prototype.handleKeys = function()
{
	// Kolla piltangenterna och �ndra paddelns riktning
	if(Key.isDown(Key.LEFT )) this.myPaddle.addSpeed(-1);
	if(Key.isDown(Key.RIGHT)) this.myPaddle.addSpeed( 1);
}

// Uppdatera positioner f�r alla objekt
Game.prototype.drawObjects = function()
{	
	for(i=0; i<this.paddles.length; i++)
		this.paddles[i].draw(); 
	for(i=0; i<this.balls.length; i++)
		this.balls[i].draw();
	for(var i=0; i<this.board.obstacles.length; i++)
		this.board.obstacles[i].draw();
}

// Testar kollisioner mellan bollar och objekt
Game.prototype.testCollisions = function()
{
	// F�r varje boll, testa kollisioner
	for(i=0; i<this.balls.length; i++)
	{
		// Studsa bollen mot spelplanens kanter. 
		// TODO: Studsa mot kant-klossarna ist�llet
		if(this.balls[i].x<30 || this.balls[i].x>600-30) this.balls[i].dx=-this.balls[i].dx;
		if(this.balls[i].y<30 || this.balls[i].y>600-30) this.balls[i].dy=-this.balls[i].dy;

		// Testa kollision med lokal paddel
		// TODO: Kolla �ven andra spelares paddlar?
		var hit = false;
		if(this.myPaddle.bounce(this.balls[i]))
			this.balls[i].move(); // Flytta ut bollen lite

		// Testa kollision med mittenklossen
		if(this.board.prize.bounce(this.balls[i]))
			this.board.prize.setVisible(false);

		// Testa kollision med ringklossarna
		hit = false
		for(var j=0; j<this.board.obstacles.length; j++)
		{			
			// Om bollen tr�ffar klossen, studsa bollen
			if(!hit && this.board.obstacles[j].bounce(this.balls[i]))
			{
				// Om bollen har studsatmot  klossen, d�lj klossen
				this.board.obstacles[j].setVisible(false);
				hit = true;
			}
		}
	}	
}
// GLOBALT KRAFS
// Ska flyttas till ... n�gon annanstans. Client, f�rslagsvis.

// Spelplanens mitt
_global.Xc = 300; 
_global.Yc = 300;
// H�ller reda p� aktuell Z-order f�r MovieClips. B�r anv�ndas av all grafik som skapas i runtime.
_global.curDepth = 1000; 

// Returnerar skal�rprodukten mellan vektorerna (x1,y1) och (x2,y2).
_global.dotPrd = function(x1, y1, x2, y2)
{
	return x1*x2 + y1*y2;
}

// Returnerar sk�rningspunkten mellan linjerna (x1,y1)-(x2,y2) och (x3,y3)-(x4,y4).
_global.intersect = function(x1, y1, x2, y2, x3, y3, x4, y4)
{
	var ua = ((x4-x3)*(y1-y3) - (y4-y3)*(x1-x3)) / ((y4-y3)*(x2-x1) - (x4-x3)*(y2-y1));
	return {x:(x1 + ua*(x2-x1)), y:(y1 + ua*(y2-y1))};
}


// Konstruktor f�r BoardObject
// Parametrar:
// centerx, centery: Den punkt som objektet roterar kring
// angle: Vinkel vid start
// instanceName: Namn p� objektets MovieClip
// clipName: Klassnamn p� MovieClip
// speed: Ursprunglig hastighet
// width: Bredd, i grader, p� objektet
// maxSpeed: Max hastighet
_global.BoardObject = function(centerx, centery, angle, instanceName, clipName, speed, 
	width, maxSpeed)
{
	// Skapa ett MovieClip
	var iobj = new Object();
	iobj._x = centerx;
	iobj._y = centery;
	iobj._rotation = angle;
	_root.attachMovie(clipName, instanceName, (++_global.curDepth), iobj);
	delete iobj;

	// Medlemsvariabler
	this.clip = _root[instanceName];
	this.angle = angle;
	this.angle0 = angle;
	this.speed = speed;
	this.width = width;
	this.maxSpeed = maxSpeed;
}

// �terst�ller klossen till dess ursprungliga position
Obstacle.prototype.reset = function()
{
	this.angle = this.angle0;
	draw();
	setVisible(true);
}

// Uppdaterar objektets MovieClip-position p� sk�rmen
BoardObject.prototype.draw = function()
{
	this.clip._rotation = this.angle;
}

// Uppdaterar objektets vinkel enligt aktuell hastighet
BoardObject.prototype.move = function()
{
	this.angle+= this.speed;
	//this.angle = (this.angle+this.speed)%360;
}

BoardObject.prototype.getAngle = function()
{
	return this.angle;
}

BoardObject.prototype.setAngle = function(angle)
{
	this.angle = angle;
}

// D�ljer/visar objektets MovieClip
BoardObject.prototype.setVisible = function(visible)
{
	this.clip._visible = visible;
}

// Ret true on objektets MovieClip �r synligt
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

// S�tter aktuell hastighet
BoardObject.prototype.setSpeed = function(speed)
{
	this.speed = speed;
	if(this.speed>this.maxSpeed) this.speed=this.maxSpeed; else
	if(this.speed<-this.maxSpeed) this.speed=-this.maxSpeed; 
}

// Testar kollision mellan en boll och detta BoardObject.
// Om kollision intr�ffar, �ndras bollens riktning och 
// true returneras, annars returneras bara false. 
// Funkar n�stan, oftast, mer eller mindre;-). Problemet 
// �r att avg�ra n�r bollen tr�ffar kortsidan. B�ttre id�er 
// mottages tacksamt.
BoardObject.prototype.bounce = function(ball)
{
	var v, nx, ny, dx, dy, l, dot, p;
	// Om bollen ligger p� objektet...
	if(this.clip._visible && this.clip.hit.hitTest(ball.x, ball.y, true))
	{
		// Ber�kna normalvektorn (nx, ny) f�r klossens l�ngsida. 
		nx = ball.x-Xc;
		ny = ball.y-Yc;
		l = Math.sqrt(nx*nx + ny*ny);
		nx/=l;
		ny/=l;
		
		// Avg�r om bollen har tr�ffat kortsidan
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
	
		// Ber�kna ny riktning
		dot = dotPrd(nx, ny, ball.dx, ball.dy);
		if(edge)
		{
			// Studsa mot kortsidan
			ball.dx = 2 * nx * dot - ball.dx;
			ball.dy = 2 * ny * dot - ball.dy;
		}
		else
		{
			// Studsa mot l�ngsidan
			ball.dx = -2 * nx * dot + ball.dx;
			ball.dy = -2 * ny * dot + ball.dy;
		}
		return true;
	}
	return false;
}

// Konstruktor f�r Board

_global.Board = function()
{
	// Medlemsvariabler
	this.obstacles = new Array();

	// Skapa klossar
	for(var i=0; i<8; i++) // Innersta ringen
		this.obstacles.push(new Obstacle(Xc, Yc, i*45, 1, 1));
	for(var i=0; i<12; i++) // Andra ringen
		this.obstacles.push(new Obstacle(Xc, Yc, i*30, 2, -2));
	for(var i=0; i<16; i++) // Tredje ringen
		this.obstacles.push(new Obstacle(Xc, Yc, i*22.5, 3, 2));
	for(var i=0; i<20; i++) // Yttersta ringen
		this.obstacles.push(new Obstacle(Xc, Yc, i*18, 4, -1));
	this.obstacles[0].setSpeed(1);
	
	// Skapa en mitt-kloss.
	this.prize = new Prize(Xc, Yc);
}