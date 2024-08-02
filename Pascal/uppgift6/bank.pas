PROGRAM bank(Input,Output);

CONST
   max = 100;

TYPE
   string_20	      = PACKED ARRAY[1..20] OF Char;
   transactionpointer = ^transaction;
   transaction	      = RECORD
			   date	  : String_20;
			   amount : Real;
			   next	  : transactionpointer;
			END;	  
   account	      = RECORD
			   name,address,phone : String_20;
			   balance, credit    : Real;
			   transactions	      : transactionpointer;
			END;		      
   accounttype	      = RECORD
			   account : ARRAY[1..max] OF Account;
			   total   : Integer;
			END;	   
   accountFile	      = FILE OF account;
   transactionFile    = FILE OF transaction;

{Läs in ett konto, kolla om det finns några transaktioner (transactions är nil om det inte finns)
Om det finns så läser vi in dem också tills next pekaren pekar på nil}
PROCEDURE load(VAR accounts :AccountType);
VAR
   i,x		     : Integer;
   pTmp,pFirst,pLast : TransactionPointer;
   aFile	     : AccountFile;
   tFile	     : TransactionFile;
   bEnd		     : Boolean;
BEGIN
   RESET(aFile,"accounts.bank");
   RESET(tFile,"transactions.bank");
   i:=1;
   WHILE NOT EOF(aFile) DO BEGIN
      Read(aFile,accounts.account[i]);
      pLast:=NIL;
      pFirst:=NIL;
      IF accounts.account[i].transactions<>NIL THEN BEGIN
	 bEnd:=FALSE;
	 REPEAT
	    NEW(pTmp);
	    Read(tFile,pTmp^);
	    IF pTmp^.next=NIL THEN bEnd:=TRUE
	    ELSE pTmp^.next:=NIL;
	    IF pLast=NIL THEN
	    BEGIN
	       pLast:=pTmp;
	       pFirst:=pTmp;
	    END
	    ELSE BEGIN
	       pLast^.next:=pTmp;
	       pLast:=pTmp;
	    END;
	 UNTIL bEnd=TRUE;
	 accounts.account[i].transactions:=pFirst;
      END;
      i:=i+1;
   END;
   accounts.total:=i-1;
END; { load }

{Spara ner båda data strukturerna i varsin fil, en för konton och en för transaktioner}
PROCEDURE save(accounts	:AccountType);
VAR
   i,x	 : Integer;
   pTmp	 : TransactionPointer;
   aFile : AccountFile;
   tFile : TransactionFile;
BEGIN 
   REWRITE(aFile,"accounts.bank");
   REWRITE(tFile,"transactions.bank");
   FOR i:=1 TO accounts.total DO BEGIN
      Write(aFile,accounts.account[i]);
      pTmp:=accounts.account[i].transactions;
      WHILE pTmp<>nil DO BEGIN
	 Write(tFile,pTmp^);
	 pTmp:=pTmp^.next
      END;
   END;
END; { save }

{sök efter ett konto i en konto array, om kontot finns retunera pos, annars 0}
FUNCTION findAccount(name : String_20;accounts:accounttype):Integer;
VAR
   found : Boolean;
   i	 : Integer;
BEGIN
   i:=1;
   found:=FALSE;
   WHILE (i<=accounts.total) AND NOT found DO
      IF accounts.account[i].name=name THEN found:=TRUE
      ELSE i:=i+1;
   IF found THEN findAccount:=i
   ELSE findAccount:=0;
END;

{skapa ett nytt unikt konto och initsiera alla värden}
PROCEDURE newAccount(VAR accounts : Accounttype);
VAR
   tmp : String_20;
BEGIN
   IF accounts.total=max THEN Writeln('Registret är fullt')
   ELSE BEGIN     
      Writeln('********** NY KUND **********');
      Write('Namn: ');
      Readln(tmp);
      IF findAccount(tmp,accounts)<>0 THEN Writeln('Det finns redan ett konto registrerat på ',tmp)
      ELSE BEGIN
	 accounts.total:=accounts.total+1;
	 WITH accounts.account[accounts.total] DO BEGIN
	    name:=tmp;
	    Write('Adress: ');
	    Readln(address);
	    Write('Telefon: ');
	    Readln(phone);
	    Write('Kredit belop: ');
	    Readln(credit);
	    IF credit>5000 THEN credit:=5000;
	    balance:=0;
	    transactions:=NIL;
	 END;
      END;
   END;
END;

{Skriv ut saldot för en vald kund eller ett fel medelande om kunden inte finns}
PROCEDURE balance(accounts : Accounttype);
VAR
   i,x		: Integer;
   name		: String_20;
   pTransaction	: Transactionpointer;
BEGIN
   Writeln('********** SALDO **********');
   Write('Namn: ');
   Readln(name);
   i:=findAccount(name,accounts);
   IF i=0 THEN Writeln('Kunden finns inte')
   ELSE BEGIN
      Writeln('********** INFORMATION OM KUNDEN **********');
      Writeln('Saldot är: ',accounts.account[i].balance:0:2);
      Writeln('Kreditbelopp: ',accounts.account[i].credit:0:2);
      pTransaction:=accounts.account[i].transactions;
      x:=0;
      Writeln('********** KUNDENS SENASTE TRANSAKTIONER **********');
      IF pTransaction=nil THEN Writeln('Inga transaktioner');
      WHILE (pTransaction<>nil) AND (x<3) DO BEGIN
	 Writeln('Transaktion från ',pTransaction^.date);
	 Writeln('Belopp: ',pTransaction^.amount:0:2);
	 x:=x+1;
	 pTransaction:=pTransaction^.next;
      END;
   END;
END;

{Skapa en ny transaktion för en vald kund om transaktionen inte överstiger saldot & krediten på kontot}
PROCEDURE newTransaction(VAR accounts : Accounttype);
VAR
   pTransaction	: Transactionpointer;
   name		: String_20;
   i		: Integer;
BEGIN
   Write('Namn: ');
   Readln(name);
   i:=findAccount(name,accounts);
   IF i=0 THEN Writeln('Kontot finns inte.')
   ELSE BEGIN
      NEW(pTransaction);
      WITH pTransaction^ DO BEGIN
	 Write('Belop: ');
	 Readln(amount);
	 Write('Datum: ');
	 Readln(date);
	 IF (accounts.account[i].credit+accounts.account[i].balance+amount)<0 THEN Writeln('För lite pengar på kontot. Max belop: ',(accounts.account[i].credit+accounts.account[i].balance):0:2)
	 ELSE BEGIN
	    next:=accounts.account[i].transactions;
	    accounts.account[i].transactions:=pTransaction;
	    accounts.account[i].balance:=accounts.account[i].balance+amount;
	 END;
      END;
   END;
END;

VAR
   accounts : Accounttype;
   ch	    : Char;
BEGIN
   accounts.total:=0;  
   load(accounts);
   REPEAT
      Writeln;
      Writeln('********** VGBank v1.0 **********');
      Writeln;
      Writeln('1. (n) Nytt konto');
      Writeln('2. (t) Ny Transaktion');
      Writeln('3. (s) Saldo');
      Writeln('4. (q) Avsluta');
      Write('[VGBank]$');
      Readln(ch);
      CASE ch OF
	'n','N','1' : newAccount(accounts);
	't','T','2' : newTransaction(accounts);
	's','S','3' : balance(accounts);
	'q','Q','4' : Writeln('Programmet Avslutas');
	Otherwise Writeln('Fel kommando');
      END; { case }
   UNTIL (ch='q') OR (ch='Q') OR (ch='4');
   save(accounts);
END.	   