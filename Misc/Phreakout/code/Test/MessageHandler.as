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