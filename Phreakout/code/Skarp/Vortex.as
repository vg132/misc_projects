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
	//Träffar bollen uppe?
	if(ball.iy == 0){
		if(this.clip._y <= this.yCornerMin && this.clip._x <= this.xCornerMin){
			//då är vortex högst upp och till vänster, dvs GÅ HÖGER (till ball.ix)
			this.clip._x += this.speed;
		}
		else if(this.clip._y <= this.yCornerMin && this.clip._x >= this.xCornerMax){
			//då är vortex högst upp och till höger, dvs GÅ VÄNSTER (till ball.ix)
			this.clip._x -= this.speed;			
		}
		else{
			//är inte vortex alls högst upp, så UPP...men kanske höger/vänster först?
			if(this.clip._x >= this.xCornerMax || this.clip._x <= this.xCornerMin){
				//då är vortex vid en kant, då är det UPP som gäller till vy = 30
				this.clip._y -= this.speed;
			}
			else{
				//då måste vortex vara mitten nere, gå höger/vänster
				if(ball.ix > ((this.xCornerMax - this.xCornerMin) / 2) && this.clip._y >= this.yCornerMax){
					//gå höger tills vx = 750
					this.clip._x += this.speed;
				}
				else if (ball.ix <= ((this.xCornerMax - this.xCornerMin) / 2) && this.clip._y >= this.yCornerMax){
					//gå vänster tills vx = 50
					this.clip._x -= this.speed;
				}
				//är vortex högst upp, fånga bollen
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
		
	//Träffar bollen nere då?
	else if(ball.iy == this.yMax){
		if(this.clip._y >= this.yCornerMax && this.clip._x <= this.xCornerMin){
			//då är vortex längst ner och till vänster, dvs GÅ HÖGER (till ball.ix)
			this.clip._x += this.speed;
		}
		else if(this.clip._y >= this.yCornerMax && this.clip._x >= this.xCornerMax){
			//då är vortex längst ner och till höger, dvs GÅ VÄNSTER (till ball.ix)
			this.clip._x -= this.speed;
		}
		else{
			//är inte vortex alls längst ner, så NER...men kanske höger/vänster först?
			if(this.clip._x >= this.xCornerMax || this.clip._x <= this.xCornerMin){
				//då är vortex vid en kant, då är det NER som gäller till vy = 550
				this.clip._y += this.speed;
			}
			else{
				//då måste vortex vara mitten uppe, gå höger/vänster
				if(ball.ix > ((this.xCornerMax - this.xCornerMin) / 2) && this.clip._y <= this.yCornerMin){
					//gå höger tills vx = 750
					this.clip._x += this.speed;
				}
				else if (ball.ix <= ((this.xCornerMax - this.xCornerMin) / 2) && this.clip._y <= this.yCornerMin){
					//gå vänster tills vx = 50
					this.clip._x -= this.speed;
				}
				//är vortex nere, fånga bollen
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
	//Kanske till vänster?
	else if (ball.ix == 0){
		if(this.clip._x <= this.xCornerMin && this.clip._y <= this.yCornerMin){
			//då är vortex längst till vänster och uppåt, dvs GÅ NER (till ball.iy)
			this.clip._y += this.speed;
		}
		else if(this.clip._x <= this.xCornerMin && this.clip._y >= this.yCornerMax){
			//då är vortex längst till vänster och neråt, dvs GÅ UPP (till ball.iy)
			this.clip._y -= this.speed;
		}
		else{
			//är inte vortex alls till vänster, så VÄNSTER...men kanske upp/ner först?
			if(this.clip._y <= this.yCornerMin || this.clip._y >= this.yCornerMax){
				//då är vortex vid en kant (uppe eller nere), då är det VÄNSTER som gäller till vx = 30
				this.clip._x -= this.speed;
			}
			else{
				//då måste vortex vara mitten höger, gå upp/ner
				if(ball.iy >= 300 && this.clip._x >= this.yCornerMax){
					//gå ner tills vy = 550
					this.clip._y += this.speed;
				}
				else if(ball.iy < ((this.yCornerMax - this.yCornerMin) / 2) && this.clip._x >= this.yCornerMax){
					//gå upp tills vy = 30
					this.clip._y -= this.speed;
				}
				//är vortex vänster, fånga bollen
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
	//Då finns det bara höger kvar
	else if (ball.ix == this.xMax){
		if(this.clip._x >= this.xCornerMax && this.clip._y <= this.yCornerMin){
			//då är vortex längst till höger och uppåt, dvs GÅ NER (till ball.iy)
			this.clip._y += this.speed;
		}
		else if(this.clip._x >= this.xCornerMax && this.clip._y >= this.yCornerMax){
			//då är vortex längst till höger och neråt, dvs GÅ UPP (till ball.iy)
			this.clip._y -= this.speed;
		}
		else{
			//är inte vortex alls till höger, så gå HÖGER...men kanske upp/ner först?
			if(this.clip._y <= this.yCornerMin || this.clip._y >= this.yCornerMax){
				//då är vortex vid en kant (uppe eller nere), då är det HÖGER som gäller till vx = 750
				this.clip._x += this.speed;
			}
			else{
				//då måste vortex vara mitten vänster, gå upp/ner
				if(ball.iy >= ((this.yCornerMax - this.yCornerMin) / 2) && this.clip._x <= this.xCornerMin){
					//gå ner tills vy = 550
					this.clip._y -= this.speed;
				}
				else if(ball.iy < ((this.yCornerMax - this.yCornerMin) / 2) && this.clip._x <= this.xCornerMin){
					//gå upp tills vy = 30
					this.clip._y -= this.speed;
				}
				//är vortex höger, fånga bollen
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


