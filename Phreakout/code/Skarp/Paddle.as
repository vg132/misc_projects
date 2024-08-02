Paddle.instanceCount = 0; // Antal paddlar som har skapats


// Konstruktor f�r Paddle
// Parametrar:
// centerx, centery: Rotationscenter
// angle: Vinkel vid start
// playerName: Spelarens namn
// paddleColor: F�rg p� paddeln. Siffra mellan 1..4
_global.Paddle = function(centerx, centery, angle, playerName, paddleColor)
{
	// Anropa superklassens konstruktor
	super(centerx, centery, angle, "paddle" + (++Paddle.instanceCount), "Paddle", 0, 20, 4);	
	if(paddleColor == undefined) paddleColor = 1;
	this.clip.gotoAndStop(paddleColor);

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

