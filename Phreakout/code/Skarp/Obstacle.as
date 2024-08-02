#include "BoardObject.as"
Obstacle.instanceCount = 0; // Antal instanser
Obstacle.widths = new Array(0, 45, 30, 22.5, 18); // Bredder för de olika nivåerna av klossar

// Konstruktor för Obstacle
// Parametrar:
// centerx, centery: Den punkt som objektet roterar kring
// angle: Vinkel vid start
// level: Ring-nivå (1=innersta ringen närmast center-klossen)
// speed: Hastighet
_global.Obstacle = function(centerx, centery, angle, level, speed, parent)
{
	// Anropa superklassens konstruktor
	super(centerx, centery, angle, "obst" add (++Obstacle.instanceCount), "Obstacle" add level, 
		speed, widths[level], 100, parent);

	// Medlemsvariabler
	this.level = level;
}

Obstacle.prototype = new BoardObject();
