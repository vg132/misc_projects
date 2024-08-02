#include "Obstacle.as"
#include "Paddle.as"
#include "Board.as"
#include "Ball.as"
#include "Prize.as"
#include "Vortex.as"

// Konstruktor för Game
_global.Game = function()
{
	this.accel = 1; // Acceleration för paddlar
	this.lastPaddle = undefined; // Senaste paddeln att träffa bollen
	this.spinFactor = 1.0; // Hur mycket bollen spinner när den träffar en paddel
	this.lives = 10;
	this.gameOver = false;

	this.ballStartSpeed = 6;
	this.ballStartDist = 150;

	// Skapa en paddel-array
	this.paddles = new Array();

	// Skapa en boll
	this.balls = new Array();
	this.balls.push(new Ball(500, 240, -6, 1));
	this.balls[0].randomPos(this.ballStartDist, this.ballStartSpeed);

	// Skapa vortex
	this.vortex=new Vortex(35,585,40,585);

	// Skapa ett bräde
	this.board = new Board();

	// Läs in ljud
	this.sounds = new Array();
	this.currentSound = 0;
	this.sounds[1] = new Sound();	this.sounds[1].attachSound("Metal 2.wav");  this.sndObstacleHit = 1;
	this.sounds[2] = new Sound();	this.sounds[2].attachSound("DISINTER.wav"); this.sndBallDie = 2;
	this.sounds[3] = new Sound();	this.sounds[3].attachSound("IMPACT02.wav"); this.sndPaddleHit = 3;
	this.sounds[4] = new Sound();	this.sounds[4].attachSound("IMPACT01.wav"); this.sndFenceHit = 4;
	this.sounds[5] = new Sound();	this.sounds[5].attachSound("ELECTRONIC.WAV"); this.sndVortexHit = 5;
}

//TODO: Fixa bättre funktion för att updatera remote paddle. Avbryt loopen när rätt paddle är hittad.
//Skicka in paddle info och identifiera en paddle med namnet på spelaren.
Game.prototype.updatePaddle=function(name, angle)
{
	for(i=0;i<this.paddles.length;i++)
	{
		if(this.paddles[i].getPlayerName()==name)
		{
			this.paddles[i].setAngle(angle);
			break;
		}
	}
}

// Uppdaterar en boll med ny position och riktning/hastighet
Game.prototype.updateBall = function(index, x, y, dx, dy)
{
	this.balls[index].x = x;
	this.balls[index].y = y;
	this.balls[index].dx = dx;
	this.balls[index].dy = dy;
}

Game.prototype.updateRing = function(index, angle, visibleArr)
{
	this.board.rings[index].angle = angle;
	for(var i=0; i<this.board.rings[index].obstacles.length; i++)
		this.board.rings[index].obstacles[i].setVisible(visibleArr[i]);
}

Game.prototype.dumpRing = function(index, visibleArr)
{
	for(var i=0; i<this.board.rings[index].obstacles.length; i++)
		visibleArr[i] = this.board.rings[index].obstacles[i].getVisible();
	return this.board.rings[index].angle;
}

Game.prototype.isGameOver=function()
{
	return this.gameOver;
}

Game.prototype.getLives=function()
{
	return this.lives;
}

Game.prototype.getCurrentSound=function()
{
	return this.currentSound;
}

Game.prototype.setCurrentSound=function(soundID)
{
	this.currentSound = soundID;
}

// Returnerar referens till en boll
Game.prototype.getBall = function(index)
{
	return this.balls[index];
}

// Inverterar piltangenterna
Game.prototype.invertControl = function()
{
	this.accel = -this.accel;
}

// Lägg till en spelare, alltså en paddel
// paddleColor: Färg på paddeln. Siffra mellan 1..4
Game.prototype.addPlayer = function(name, angle, localPlayer, paddleColor)
{
	var p = new Paddle(Xc, Yc, angle, name, paddleColor);
	this.paddles.push(p);
	if(localPlayer)
		this.myPaddle = p;
}

// Ny iter av gameloopen
Game.prototype.gameLoop = function(updateBall)
{
	if(this.gameOver)
		return;
	if(this.currentSound!=0)
	{
		this.sounds[this.currentSound].start(0, 1);
		this.currentSound = 0;
	}

	this.handleKeys();
	if(updateBall)
	{
		this.moveObjects();
		this.moveBalls();
		this.moveVortex();
		this.testCollisions();
	}
	else
		this.myPaddle.move();
	this.drawObjects();
}

/*
// Ny iter av gameloopen
Game.prototype.gameLoop = function(updateBall)
{
	if(this.gameOver)
		return;
	this.handleKeys();
	this.moveObjects();
	if(updateBall)
		this.moveBalls();
	this.moveVortex();
	this.testCollisions();
	this.drawObjects();
}*/

// Flyttar alla objekt
Game.prototype.moveObjects = function()
{
	var i=0;
	// Flytta paddlarna
	for(i=0; i<this.paddles.length; i++)
		this.paddles[i].move(); 

	// Flytta ring-klossarna
	for(i=0; i<this.board.rings.length; i++)
		this.board.rings[i].move();
}

// Låt vortex jaga bollarna
Game.prototype.moveVortex=function()
{
	for(i=0;i<this.balls.length;i++)
		this.vortex.chaseBall(this.balls[i]);
}

// Flytta bollarna
Game.prototype.moveBalls = function()
{	
	for(i=0; i<this.balls.length; i++)
		this.balls[i].move();
}

// Hanterar knapptryckningar
Game.prototype.handleKeys = function()
{
	// Kolla piltangenterna och ändra paddelns riktning
	if(Key.isDown(Key.HOME )) this.myPaddle.setSpeed( 0);
	if(Key.isDown(Key.LEFT )) this.myPaddle.addSpeed(-this.accel);
	if(Key.isDown(Key.RIGHT)) this.myPaddle.addSpeed( this.accel);
}

// Uppdatera positioner för alla objekt
Game.prototype.drawObjects = function()
{	
	for(i=0; i<this.paddles.length; i++)
		this.paddles[i].draw(); 
	for(i=0; i<this.balls.length; i++)
		this.balls[i].draw();
	for(var i=0; i<this.board.rings.length; i++)
		this.board.rings[i].draw();
}

Game.prototype.clearGame=function()
{
	for(i=0;i<this.board.rings.length;i++)
	{
		for(ii=0;ii<this.board.rings[i].obstacles.length;ii++)
		{
			this.board.rings[i].obstacles[ii].clip.removeMovieClip();
		}
	}
	for(var i=0; i<this.board.fences.length; i++)
		if(this.board.fences[i]!=undefined)
			this.board.fences[i].clip.removeMovieClip();

	for(i=0;i<this.paddles.length;i++)
	{
		this.paddles[i].clip.removeMovieClip();
	}
	for(i=0; i<this.balls.length; i++)
	{
		this.balls[i].clip.removeMovieClip();
	}
	this.board.prize.clip.removeMovieClip();
	for(i=0;i<this.paddles.length;i++)
	{
		this.paddles[i].clip.removeMovieClip();
	}

	for(var i=0; i<this.fences.length; i++)
		if(this.fences[i]!=undefined)
			this.board.fences[i].clip.removeMovieClip();
	
	this.vortex.clip.removeMovieClip();
}

// Återställer spelet till utgångsläget och påbörjar nästa nivå.
Game.prototype.nextLevel = function()
{
	this.board.reset();
	this.balls[0].randomPos(this.ballStartDist, this.ballStartSpeed);
}

// Anropas när en boll har förlorats. Returnerar true om spelet ska fortsätta.
Game.prototype.loseBall = function()
{
	if((--this.lives)>0)
	{
		for(i=0; i<this.balls.length; i++)
		{
			this.balls[0].randomPos(this.ballStartDist, this.ballStartSpeed);
		}
	}
	else
	{
		this.gameOver = true;
	}
}

// Testar kollisioner mellan bollar och objekt
Game.prototype.testCollisions = function()
{
	// För varje boll, testa kollisioner
	for(i=0; i<this.balls.length; i++)
	{
		var hit = false; // Bollen har inte träffat något ännu

		// Testa kollision med paddlar
		for(var j=0; !hit && j<this.paddles.length; j++)
		{
			if(!hit && this.paddles[j].bounce(this.balls[i]))
			{
				this.balls[i].move(); // Flytta ut bollen lite
				this.balls[i].turn(this.paddles[j].getSpeed()*this.spinFactor); // Lägg till lite spinn
				this.lastPaddle = this.paddles[j];
				hit = true;
				this.currentSound = this.sndPaddleHit;
			}
			/*
			// Kolla mot andra paddlar
			for(var k=j+1; k<this.paddles.length; k++)
			{
				var dist = Math.abs(this.paddles[j].angle%360-this.paddles[k].angle%360);
				if(dist<this.paddles[k].width || dist>360-this.paddles[k].width)
				{
					this.paddles[j].speed = -this.paddles[j].speed;
					this.paddles[k].speed = -this.paddles[k].speed;
					this.paddles[j].move();
					this.paddles[k].move();
				}
			}*/
		}

		// Kolla om den träffar vortex
		this.vortex.impact(this.balls[i]);
		if(!hit && this.vortex.clip.hitTest(this.balls[i].x, this.balls[i].y, true))
		{
			this.loseBall();
			this.currentSound = this.sndVortexHit;
			hit = true;
		}

		// Kolla kant-klossar
		if(!hit)
		{
			var f = this.board.getFence(this.balls[i].x, this.balls[i].y);
			if(f!=undefined && f.getVisible())
			{
				if(this.balls[i].x<=this.board.edgeLeft)   this.balls[i].x =this.board.edgeLeft;
				if(this.balls[i].y<=this.board.edgeTop)    this.balls[i].y = this.board.edgeTop;
				if(this.balls[i].y>=this.board.edgeBottom) this.balls[i].y = this.board.edgeBottom;
				if(this.balls[i].x>=this.board.edgeRight)  this.balls[i].x = this.board.edgeRight;
				f = this.board.getFence(this.balls[i].x, this.balls[i].y);
			}

			if(f!=undefined && f.getVisible())
			{
				this.currentSound = this.sndFenceHit;
				if(f.bounce(this.balls[i]))
				{
					f.setVisible(false);
					if(this.lastPaddle!=undefined)
						this.lastPaddle.addScore(5);
				}
				this.balls[i].move(); // Flytta ut bollen lite
			}
		}

		// Testa kollision med mittenklossen
		if(!hit && this.board.prize.bounce(this.balls[i]))
		{
			this.board.prize.setVisible(false);
			if(this.lastPaddle!=undefined)
				this.lastPaddle.addScore(100);
			this.nextLevel();
			this.currentSound = this.sndObstacleHit;
			hit = true;
		}
		
		if(!hit)
		{
			// Testa kollision med ringarna
			for(var r=0; !hit && r<this.board.rings.length; r++)
			{
				// Kolla om bollen finns innaför ringens yttarkant
				if(this.board.rings[r].hitTest(this.balls[i].x, this.balls[i].y))
				{
					// Testa kollision mot de klossar som ligger i ringen
					for(var j=0; !hit && j<this.board.rings[r].obstacles.length; j++)
					{
						// Om bollen träffar klossen, studsa bollen
						if(!hit && this.board.rings[r].obstacles[j].bounce(this.balls[i]))
						{
							// Om bollen har studsatmot klossen, dölj klossen
							this.board.rings[r].obstacles[j].setVisible(false);
							if(this.lastPaddle!=undefined)
								this.lastPaddle.addScore(10);
							hit = true;
							this.currentSound = this.sndObstacleHit;
						}
					}
				}
			}
		}

		// Kolla om bollen har hamnat utanför spelplanen
		if(!hit && (this.balls[i].x<0 || this.balls[i].x>600 || this.balls[i].y<0 || this.balls[i].y>600))
		{
			this.loseBall();
			this.currentSound = this.sndBallDie;
			hit = true;
		}
	}
}

