PROGRAM Uppgift1 (Input,Output);
VAR
   date		    : INTEGER;
   curYear	    : INTEGER;
   year, month, day : INTEGER;
BEGIN
   curYear:=2002;
   Writeln('V�llkomen till Viktors �lders ber�knare');
   Writeln;
   Write('Ditt f�delse nummer: ');
   Readln(date);
   year:=((date DIV 10000) MOD 100);
   month:=((date DIV 100) MOD 100);
   IF year<10 THEN
      year:=date+2000
   ELSE IF year<100 THEN
      year:=year+1900;
   Write('Du �r ',curYear-year,' �r gammal/ung och f�dd p� ');
   CASE month OF
     11,12,1,2 : Write('vintern');
     3,4       : Write('v�ren');
     5..8      : Write('sommar');
     9,10      : Write('h�sten');
   END;
   Writeln;
END.
