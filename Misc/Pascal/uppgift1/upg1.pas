PROGRAM Uppgift1 (Input,Output);
VAR
   date		    : INTEGER;
   curYear	    : INTEGER;
   year, month, day : INTEGER;
BEGIN
   curYear:=2002;
   Writeln('Vällkomen till Viktors ålders beräknare');
   Writeln;
   Write('Ditt födelse nummer: ');
   Readln(date);
   year:=((date DIV 10000) MOD 100);
   month:=((date DIV 100) MOD 100);
   IF year<10 THEN
      year:=date+2000
   ELSE IF year<100 THEN
      year:=year+1900;
   Write('Du är ',curYear-year,' år gammal/ung och född på ');
   CASE month OF
     11,12,1,2 : Write('vintern');
     3,4       : Write('våren');
     5..8      : Write('sommar');
     9,10      : Write('hösten');
   END;
   Writeln;
END.
