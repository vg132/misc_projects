Paddle.instanceCount = 0; // Antal paddlar som har skapats


// Konstruktor för Paddle
// Parametrar:
// centerx, centery: Rotationscenter
// angle: Vinkel vid start
// playerName: Spelarens namn
// paddleColor: Färg på paddeln. Siffra mellan 1..4
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

// Lägger till poäng
Paddle.prototype.addScore = function(score)
{
	this.score+= score;
}

// Sätter spelarens poäng
Paddle.prototype.setScore = function(score)
{
	this.score = score;
}

// Returnerar spelarens poäng
Paddle.prototype.getScore = function()
{
	return this.score;
}

// Ret spelarens namn
Paddle.prototype.getPlayerName = function()
{
	return this.playerName;
}

