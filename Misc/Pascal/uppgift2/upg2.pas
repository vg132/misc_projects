Program Uppgift2(Input,Output);
Var
   kontotyp	      : char;
   belop, ranta,summa : double;
   start,slut,x	      : integer;
begin
   start:=2002;
   ranta:=0;
   Writeln('Byk�pings Bank');
   Writeln;
   REPEAT
      Write('Ange kontotyp: ');
      Readln(kontotyp);
      CASE kontotyp OF
	'A','a'	  : ranta:=0.07;
	'B','b'	  : ranta:=0.08;
	'C','c'	  : ranta:=0.085;
	OTHERWISE Writeln('Fel kontotyp. F�rs�k igen.');
      END;
   UNTIL ranta<>0;
   REPEAT
      Write('Ange beh�llning: ');
      readln(belop);
      IF belop<=0 THEN
	 Writeln('Beloppet m�ste vara positivt.');
   UNTIL belop>0;
   REPEAT
      Write('Ange slut�r: ');
      readln(slut);
      IF slut<=start THEN
	 Writeln('Slut �ret m�ste vara ett �r efter ',start,'.');
   UNTIL slut>start;
   summa:=belop;
   Writeln(start,'       ',belop:0:2);
   FOR x:=start+1 TO slut DO BEGIN
      summa:=summa*(1+ranta);
      Writeln(x,'       ',summa:0:2);
   END;
END.