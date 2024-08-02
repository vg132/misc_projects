PROGRAM Uppgift3(Input,Output);
VAR
   value    : array[1..10] of integer;
   i, iLoop : integer;
BEGIN 
   Writeln('Viktors Stapel Program');
   Writeln;
   WHILE i<10 DO
   BEGIN
      Write('Stapel ',i+1,'>');
      Readln(value[i]);
      CASE value[i] OF
	1..25:i:=i+1;
	Otherwise Writeln('Fel, skall vara mellan 1 och 25. Försök igen!');
      END;
   END;
   Write('Arrayen: ');
   FOR i:=0 TO 9 DO
      Write(value[i],', ');
   Writeln;
   Writeln('Här är ett liggande stapeldiagram:');
   FOR i:=0 TO 9 DO
   BEGIN
      FOR iLoop:=1 TO value[i] DO
	 Write('*');
      Writeln;
   END;
   Writeln('Här är ett stående stapeldiagram:');
   FOR i:=25 DOWNTO 1 DO
   BEGIN
      FOR iLoop:=0 TO 9 DO
      BEGIN
	 IF value[iLoop]>=i THEN
	    Write('* ')
	 ELSE
	    Write('  ');
      END;
      Writeln;
   END;
END.