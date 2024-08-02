//------------------MessageHandler.as-------------------------

//Local variable
var myURL;
var myPort;
var mySocket;
var myClient;

//Function hooks
var onHostlist;
var onHighscore;
var onPlayerlist;
var onUserlist
var onDefault;
var onStart;
var onSync;

function MessageHandler(url,port)
{
	this.myURL=url;
	this.myPort=port;
	this.myClient=null;
	this.onHostlist=null;
	this.onPlayerlist=null;
	this.onDefault=null;
	this.onStart=null;
	this.onSync=null;
	this.onUserlist=null;
	this.mySocket=new XMLSocket();
	this.mySocket._this=this;
	this.mySocket.onData=this.myOnData;
}

// Retunerar socketen som är ansluten till servern
MessageHandler.prototype.getSocket=function()
{
	return(mySocket);
}

// Ta reda på vad för data som har kommit in och gör olika saker beroende på vad det är
MessageHandler.prototype.myOnData=function(src)
{
	var st=new StringTokenizer(src,"|");
	var command=st.nextToken();

	if(command=="HOSTLIST")
		this._this.onHostlist(this._this.myClient,st);
	else if(command=="PLAYERLIST")
		this._this.onPlayerlist(this._this.myClient,st);
	else if(command=="START")
		this._this.onStart(this._this.myClient,st);
	else if(command=="SYNC")
		this._this.onSync(this._this.myClient,st);
	else if(command=="USERLIST")
		this._this.onUserlist(this._this.myClient,st);
	else if(command=="HIGHSCORE")
		this._this.onHighscore(this._this.myClient,st);
	else
		this._this.onDefault(this._this.myClient,command,src);
}

// Öppna anslutningen till en server
MessageHandler.prototype.connect=function()
{
	this.mySocket.connect(this.myURL,this.myPort);
}

//När en spelare vill logga in
MessageHandler.prototype.login=function(username)
{
	this.sendData("CONNECT|"+username);
}

// Hjälp funktion för att sända data till servern
MessageHandler.prototype.sendData=function(data)
{
	this.mySocket.send(data+"\n");
}

// När en spelare vill avsluta hela spelet.
MessageHandler.prototype.closeConnection=function()
{
	this.sendData("QCONNECT");
}

// När en spelare vill skapa ett nytt spel
MessageHandler.prototype.host=function(gameName,players)
{
	this.sendData("HOST|"+gameName+"|"+players);
}

// Avslutar hela spelet för alla spelare
MessageHandler.prototype.quitGame=function()
{
	this.sendData("QGAME");
}

// När en spelare vill ansluta till ett spel
MessageHandler.prototype.join=function(gameName)
{
	this.sendData("JOIN|"+gameName);
}

// När en spelare lämnar game lobbyn
MessageHandler.prototype.exitGame=function()
{
	this.sendData("QJOIN");
}

// Begär att servern ska skicka en hostlist.
MessageHandler.prototype.getHostlist=function()
{
	this.sendData("HOSTLIST");
}

// skicka ett chat medelande
MessageHandler.prototype.chat=function(message)
{
	this.sendData("CHAT|"+message);
}

// Starta ett nytt spel
MessageHandler.prototype.start=function(angle)
{
	this.sendData("START|"+angle);
}

// Paddle positionen skickas till alla spelare
MessageHandler.prototype.paddleUpdate=function(name,angle)
{
	this.sendData("PADDLE|"+name+"|"+angle);
}

//  Boll sync skickas till alla spelare.
MessageHandler.prototype.sync=function(ball_x,ball_y,ball_dx,ball_dy,rings,vortex_x,vortex_y,sound,points)
{
	this.sendData("SYNC|" + ball_x + "|" + ball_y + "|" + ball_dx + "|" + ball_dy+ "|" +rings+"|"+vortex_x+"|"+vortex_y+"|"+sound+"|"+points);
}

// Skicka poängen till servern för att läggas in i en highscore lista
MessageHandler.prototype.highScore=function(score)
{
	this.sendData("HIGHSCORE|"+score);
}

MessageHandler.prototype.getHighscore=function()
{
	this.sendData("HIGHSCORE");
}
//------------------------------------------------------------
