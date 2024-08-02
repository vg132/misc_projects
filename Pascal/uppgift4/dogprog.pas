PROGRAM dogprog(Input, Output);
CONST
   max	   = 25;
   taxtail = 3.7;
TYPE
   string_20 = PACKED ARRAY[1..20] OF Char; 
   dogtype   = RECORD
		  name, ras   : string_20;
		  age	      : Integer;
		  weight,tail : Real;
	       END;	      
   dogrec    = ARRAY[1..max] OF dogtype;

VAR
   reg	      : dogrec;
   dogcount,i : Integer;
   ch	      : Char;
   tax	      : String_20;

BEGIN
   Writeln("DogProg");
   dogcount:=0;
   tax:='Tax';
   REPEAT
      WITH reg[dogcount] DO BEGIN	 
	 Write("Hundens Namn: ");
	 Readln(name);
	 Write("Ras: ");
	 Readln(ras);
	 Write("Ålder: ");
	 Readln(age);
	 Write("Vikt: ");
	 Readln(weight);
	 IF ras<>tax THEN
	    tail:=age*weight/10
	 else
	    tail:=taxtail;
      END;
      Write("Vill du registrera en till hund? (J/N) ");
      Readln(ch);
      dogcount:=dogcount+1;
   UNTIL (ch='n') OR (ch='N');
   FOR i:=0 TO dogcount-1 DO BEGIN
      IF reg[i].tail>10 THEN BEGIN
	 WITH reg[i] DO BEGIN
	    Writeln("Namn: ",name);
	    Writeln("Ras: ",ras);
	    Writeln("Ålder: ",age:0:0);
	    Writeln("Svans längd: ",tail:0:2);
	    Writeln;
	 END;
      END;
   END;
END.