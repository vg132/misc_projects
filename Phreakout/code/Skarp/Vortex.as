//CLASS Vortex
_global.Vortex = function(xMin, xMax, yMin, yMax)
{
	var iobj = {_x:x, _y:y, _name:"Vortex" add (++Ball.instanceCount)};
	attachMovie("Vortex", iobj._name, (++_global.curDepth), iobj);
	this.clip = _root[iobj._name];
	this.clip._x = 50;
	this.clip._y = 220;
	this.speed = 10;
	this.yMin = yMin;
	this.yMax = yMax;
	this.xMin = xMin;
	this.xMax = xMax;
	this.yCornerMin = yMin + 30;
	this.yCornerMax = yMax - 50;
	this.xCornerMin = xMin + 50;
	this.xCornerMax = xMax - 50;
}

Vortex.prototype.setX = function(xPos){
	this.clip._x= xPos;
}

Vortex.prototype.getX = function(){
		return(this.clip._x);
}

Vortex.prototype.setY = function(yPos){
	this.clip._y=yPos;
}

Vortex.prototype.getY = function(){
	return(this.clip._y);
}

Vortex.prototype.setSpeed = function (newSpeed){
	this.speed = newSpeed;
}

Vortex.prototype.getSpeed = function (newSpeed){
	return this.speed;
}


Vortex.prototype.chaseBall = function(ball){
	//Tr�ffar bollen uppe?
	if(ball.iy == 0){
		if(this.clip._y <= this.yCornerMin && this.clip._x <= this.xCornerMin){
			//d� �r vortex h�gst upp och till v�nster, dvs G� H�GER (till ball.ix)
			this.clip._x += this.speed;
		}
		else if(this.clip._y <= this.yCornerMin && this.clip._x >= this.xCornerMax){
			//d� �r vortex h�gst upp och till h�ger, dvs G� V�NSTER (till ball.ix)
			this.clip._x -= this.speed;			
		}
		else{
			//�r inte vortex alls h�gst upp, s� UPP...men kanske h�ger/v�nster f�rst?
			if(this.clip._x >= this.xCornerMax || this.clip._x <= this.xCornerMin){
				//d� �r vortex vid en kant, d� �r det UPP som g�ller till vy = 30
				this.clip._y -= this.speed;
			}
			else{
				//d� m�ste vortex vara mitten nere, g� h�ger/v�nster
				if(ball.ix > ((this.xCornerMax - this.xCornerMin) / 2) && this.clip._y >= this.yCornerMax){
					//g� h�ger tills vx = 750
					this.clip._x += this.speed;
				}
				else if (ball.ix <= ((this.xCornerMax - this.xCornerMin) / 2) && this.clip._y >= this.yCornerMax){
					//g� v�nster tills vx = 50
					this.clip._x -= this.speed;
				}
				//�r vortex h�gst upp, f�nga bollen
				else if(this.clip._y <= this.yCornerMin && (ball.ix - this.clip._x) > 10){
					this.clip._x += this.speed;
				}
				else if(this.clip._y <= this.yCornerMin && (ball.ix - this.clip._x) < -10){
					this.clip._x -= this.speed;
				}
				else{
					this.clip._x = ball.ix;
				}
			}
		}
	}
		
	//Tr�ffar bollen nere d�?
	else if(ball.iy == this.yMax){
		if(this.clip._y >= this.yCornerMax && this.clip._x <= this.xCornerMin){
			//d� �r vortex l�ngst ner och till v�nster, dvs G� H�GER (till ball.ix)
			this.clip._x += this.speed;
		}
		else if(this.clip._y >= this.yCornerMax && this.clip._x >= this.xCornerMax){
			//d� �r vortex l�ngst ner och till h�ger, dvs G� V�NSTER (till ball.ix)
			this.clip._x -= this.speed;
		}
		else{
			//�r inte vortex alls l�ngst ner, s� NER...men kanske h�ger/v�nster f�rst?
			if(this.clip._x >= this.xCornerMax || this.clip._x <= this.xCornerMin){
				//d� �r vortex vid en kant, d� �r det NER som g�ller till vy = 550
				this.clip._y += this.speed;
			}
			else{
				//d� m�ste vortex vara mitten uppe, g� h�ger/v�nster
				if(ball.ix > ((this.xCornerMax - this.xCornerMin) / 2) && this.clip._y <= this.yCornerMin){
					//g� h�ger tills vx = 750
					this.clip._x += this.speed;
				}
				else if (ball.ix <= ((this.xCornerMax - this.xCornerMin) / 2) && this.clip._y <= this.yCornerMin){
					//g� v�nster tills vx = 50
					this.clip._x -= this.speed;
				}
				//�r vortex nere, f�nga bollen
				else if(this.clip._y >= this.yCornerMax && (ball.ix - this.clip._x) > 10){
					this.clip._x += this.speed;
				}
				else if(this.clip._y >= this.yCornerMax && (ball.ix - this.clip._x) < -10){
					this.clip._x -= this.speed;
				}
				else{
					this.clip._x = ball.ix;
				}
			}
		}
	}
	//Kanske till v�nster?
	else if (ball.ix == 0){
		if(this.clip._x <= this.xCornerMin && this.clip._y <= this.yCornerMin){
			//d� �r vortex l�ngst till v�nster och upp�t, dvs G� NER (till ball.iy)
			this.clip._y += this.speed;
		}
		else if(this.clip._x <= this.xCornerMin && this.clip._y >= this.yCornerMax){
			//d� �r vortex l�ngst till v�nster och ner�t, dvs G� UPP (till ball.iy)
			this.clip._y -= this.speed;
		}
		else{
			//�r inte vortex alls till v�nster, s� V�NSTER...men kanske upp/ner f�rst?
			if(this.clip._y <= this.yCornerMin || this.clip._y >= this.yCornerMax){
				//d� �r vortex vid en kant (uppe eller nere), d� �r det V�NSTER som g�ller till vx = 30
				this.clip._x -= this.speed;
			}
			else{
				//d� m�ste vortex vara mitten h�ger, g� upp/ner
				if(ball.iy >= 300 && this.clip._x >= this.yCornerMax){
					//g� ner tills vy = 550
					this.clip._y += this.speed;
				}
				else if(ball.iy < ((this.yCornerMax - this.yCornerMin) / 2) && this.clip._x >= this.yCornerMax){
					//g� upp tills vy = 30
					this.clip._y -= this.speed;
				}
				//�r vortex v�nster, f�nga bollen
				else if(this.clip._x <= this.xCornerMin && (ball.iy - this.clip._y) > 10){
					this.clip._y += this.speed;
				}
				else if(this.clip._x <= this.xCornerMin && (ball.iy - this.clip._y) < -10){
					this.clip._y -= this.speed;
				}
				else{
					this.clip._y = ball.iy;
				}
			}
		}
	}
	//D� finns det bara h�ger kvar
	else if (ball.ix == this.xMax){
		if(this.clip._x >= this.xCornerMax && this.clip._y <= this.yCornerMin){
			//d� �r vortex l�ngst till h�ger och upp�t, dvs G� NER (till ball.iy)
			this.clip._y += this.speed;
		}
		else if(this.clip._x >= this.xCornerMax && this.clip._y >= this.yCornerMax){
			//d� �r vortex l�ngst till h�ger och ner�t, dvs G� UPP (till ball.iy)
			this.clip._y -= this.speed;
		}
		else{
			//�r inte vortex alls till h�ger, s� g� H�GER...men kanske upp/ner f�rst?
			if(this.clip._y <= this.yCornerMin || this.clip._y >= this.yCornerMax){
				//d� �r vortex vid en kant (uppe eller nere), d� �r det H�GER som g�ller till vx = 750
				this.clip._x += this.speed;
			}
			else{
				//d� m�ste vortex vara mitten v�nster, g� upp/ner
				if(ball.iy >= ((this.yCornerMax - this.yCornerMin) / 2) && this.clip._x <= this.xCornerMin){
					//g� ner tills vy = 550
					this.clip._y -= this.speed;
				}
				else if(ball.iy < ((this.yCornerMax - this.yCornerMin) / 2) && this.clip._x <= this.xCornerMin){
					//g� upp tills vy = 30
					this.clip._y -= this.speed;
				}
				//�r vortex h�ger, f�nga bollen
				else if(this.clip._x >= this.xCornerMax && (ball.iy - this.clip._y) > 10){
					this.clip._y += this.speed;
				}
				else if(this.clip._x >= this.xCornerMax && (ball.iy - this.clip._y) < -10){
					this.clip._y -= this.speed;
				}
				else{
					this.clip._y = ball.iy;
				}
			}
		}
	}
}

Vortex.prototype.impact = function(ball){
	
	ball.ix = ball.clip._x;
	ball.iy = ball.clip._y;
	var b = true;
	
	if(ball.dx != 0 && ball.dy != 0){
		while(b){
			ball.ix += ball.dx;
			ball.iy += ball.dy;
			if(ball.ix < 0){
				ball.ix = 0;
				b = false;
			}
			if(ball.ix > this.xMax){
				ball.ix = this.xMax;
				b = false;
			}
			if(ball.iy < 0){
				ball.iy = 0;
				b = false;
			}
			if(ball.iy > this.yMax){
				ball.iy = this.yMax;
				b = false;
			}		
		}//while
	}//if
}


