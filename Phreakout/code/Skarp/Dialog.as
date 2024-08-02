// Klass som visar en dialog ruta för användaren på en vald plats (x & y) och med en
// vald text och rubrik.

_global.Dialog=function()
{
	this.clip=attachMovie("Dialog", "Dialog1",1);
	this.clip._visible=false;
}

// Set titeln
Dialog.prototype.setTitle=function(title)
{
	this.clip.strTitle=title;
}
this.clip.ExitButon.onPress=function()
{
	trace("Kalle Anka");
}
// Sett aktuellt medelande
Dialog.prototype.setMessage=function(message)
{
	this.clip.strMessage=message;
}

// Sett aktuel position för rutan
Dialog.prototype.setPos=function(x,y)
{
	this.clip._x=x;
	this.clip._y=y;
}

// Visa eller dölj dialog rutan
Dialog.prototype.toggleDialog=function()
{
	this.clip._visible=!this.clip._visible;
}