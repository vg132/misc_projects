// Enkel stringtokenizer klass.
//
// var st=new StringTokenizer("Kalle|Anka|Heter|Jag|Inte","|");
// while(st.hasMoreTokens())
//   trace(st.nextToken());
//
var startPos;
var endPos;
var maxPos;
var delimiter;
var string;

//Skapa nytt tokenizer objekt med en sträng och en avskiljare
_global.StringTokenizer = function(str,delim)
{
	this.string=str;
	this.endPos=0;
	this.startPos=0;
	this.maxPos=str.length;
	this.delimiter=delim;
}

// Kolla om det finns fler tokens i strängen
StringTokenizer.prototype.hasMoreTokens=function()
{
	if((this.string.indexOf(this.delimiter,this.startPos))&&(this.startPos<this.maxPos))
		return(true);
	else
		return(false);
}

// Count the total nr of tokens in this string.
StringTokenizer.prototype.countTokens=function()
{
	var count=0;
	if(this.string.length>0)
	{
		var tmp=0;
		do
		{
			count++;
		}while((tmp=this.string.indexOf(this.delimiter,tmp)+this.delimiter.length)!=((-1)+this.delimiter.length))
	}
	return(count);
}

// Count the nr of tokens left in this string.
StringTokenizer.prototype.tokensLeft=function()
{
	var count=0;
	if(this.startPos<this.maxPos)
	{
		var tmp=this.startPos;
		do
		{
			count++;
		}
		while((tmp=this.string.indexOf(this.delimiter,tmp)+this.delimiter.length)!=((-1)+this.delimiter.length))
	}
	return(count);
}

// Retunera nästa token, null om det inte finns någon mer.
StringTokenizer.prototype.nextToken=function()
{
	if(this.startPos<this.maxPos)
	{
		this.endPos=this.string.indexOf(this.delimiter,this.startPos);
		if(this.endPos==-1)
			this.endPos=this.maxPos;
		var tmp=this.string.substr(this.startPos,this.endPos-this.startPos);
		this.startPos=this.endPos+this.delimiter.length;
		return(tmp);
	}
	return(null);
}
