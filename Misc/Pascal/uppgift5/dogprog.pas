PROGRAM dogprog(Input, Output);
CONST
   taxtail = 3.7;
TYPE
   string_20  = PACKED ARRAY[1..20] OF Char;
   dogpointer = ^dogtype;
   dogtype    = RECORD
		   name, ras   : string_20;
		   age	       : Integer;
		   weight,tail : Real;
		   next	       : dogpointer;
		END;
VAR
   ch		 : Char;
   first,current : dogpointer;
   
{Add new dog to the begining of the linked list}
PROCEDURE newDog;
VAR
   tax : String_20;
BEGIN
   tax:='Tax';
   NEW(current);
   current^.next:=NIL;
   WITH current^ DO BEGIN
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
      ELSE
	 tail:=taxtail;
   END;
   current^.next:=first;
   first:=current;
END;

{Remove all dogs with ras equals to 'Pudel'}
PROCEDURE killPoodles;
VAR
   temp	  : dogpointer;
   poodle : String_20;
BEGIN
   poodle:='Pudel';
   IF first<>NIL THEN BEGIN
      current:=first;
      temp:=first;
      REPEAT
	 IF current^.ras=poodle THEN
	 BEGIN
	    IF current=first THEN
	    BEGIN
	       first:=first^.next;
	       temp:=first;
	    END
	    ELSE
	       temp^.next:=current^.next;
	    DISPOSE(current);
	    current:=temp;
	 END;
	 temp:=current;
	 IF (current<>NIL) AND (current^.ras<>poodle) THEN current:=current^.next;
      UNTIL current=NIL;
   END;
END;

{Print the dogs onto the screen}
PROCEDURE printDogReg;
BEGIN
   IF first<>NIL THEN BEGIN
      Writeln('Namn           Ras            Ålder   Svans längd');
      current:=first;
      REPEAT
	 IF current^.tail>10 THEN BEGIN
	    WITH current^ DO BEGIN
	       Write(name:15);
	       Write(ras:15);
	       Write(age:5:0);
	       Writeln(tail:14:2);
	    END;
	 END;
	 current:=current^.next;
      UNTIL current=NIL;
   END;
END;

BEGIN
   first:=NIL;
   current:=NIL;
   Writeln("DogProg v1.1");
   REPEAT
      newDog;
      Write("Vill du registrera en till hund? (J/N) ");
      Readln(ch);
   UNTIL (ch='n') OR (ch='N');
   printDogReg;
   Writeln('Please stand by, searching for killer Poodles...');
   killPoodles;
   Writeln('All killer Poodles have been terminated!');
   printDogReg;
END.