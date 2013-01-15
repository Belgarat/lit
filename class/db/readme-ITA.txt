CP_CONNECT - livello di astrazione database
----------------------------------------------
*Documentazione*

Autore: Belgarat
Revisioni: Klown 20081204 -> correzioni minori ed adattamento fine riga
File:

db.php -> File di configurazione
database.php -> Classe principale da includere nelle pagine
query.php -> Classe che gestisce le query
db_mysql -> Libreria di funzioni per mysql

----------------------------------------------


In una prima fase vengono raccolti tutti i dati essenziali per poter 
eseguire una query, come nome delle tabelle, dei campi, filtri ecc...
Nella seconda fase queste informazioni vengono passate ai moduli 
specifici riguardanti il database in uso.

Nello specifico si possono distinguere due tipi di query e quindi di 
dati necessari per eseguirle:


Cap. 1 - Query di selezione
		- campi da prelevare
		- tabelle di lavoro
		- filtri
		- raggruppamenti
		- filtri sui raggruppamenti (having)
		- ritorna un array bidimensionale con le righe e le colonne dei
		  risultati


Cap. 2 - Query di comando:
		- comando (insert, drop, delete, ...)
		- campi destinazione
		- tabelle destinazione
		- filtri


***************************************
****Capitolo 1 - Query di selezione****
***************************************


Entrando nello specifico possiamo ora andare a vedere effettivamente 
come vengono eseguite delle query utilizzando il nostro oggetto.

Viene creato l'oggetto query:

	$q = new Query();

Viene specificata la tabella su cui operare:

	$q->tables = array("tab_provami");

Successivamente vengono passati i campi da estrarre, ovviamente 
specificando un * fra apici vengono scelti tutti i campi della tabella:

	$q->fields = array("codice", "nome", "cognome", "anno");

Viene impostato il filtro come fosse una normale istruzione where:

	$q->filters = "cognome like 'r%'";

Vengono quindi specificati eventuali raggruppamenti:

	$q->groupby = array("codice", "nome", "cognome", "anno");

Si ha la possibilità inoltre di aggiungere un ulteriore filtro 
all'interno dei raggruppamenti attraverso il campo:

	$q->having = "anno=1976";

Vengono quindi specificati i campi da ordinare, se non viene specificato
altro i campi sono ordinati in maniera ascendente ("asc"):
 
	$q->sortfields = array("anno");

Ed infine definiamo il numero di record da estrarre:

	$q->limit = "0,1";

Una volta specificati tutti i campi necessari per la nostra query (sono 
possibili anche le query fra due o più tabelle ma non le vedremo in 
questo capitolo) possiamo andare ad eseguire la nostra interrogazione; 
il metodo Open() esegue il nostro  codice SQL e restituisce un esito che
può essere "True" se tutto è andato come doveva e "False" nel caso si siano 
riscontrati degli errori.

	if ($q->Open() ) {
		echo "<br>Query riuscita<br>";

In caso di successo della nostra query abbiamo i dati estratti e a 
disposizione.
Per poterli manipolare viene utilizzato un metodo simile a quello ADO, 
attraverso il metodo GetNextRecord() viene restituito un array con tutti
i campi precedentemente indicati, questi possono essere memorizzati in 
una variabile, nel nostro esempio $row, che viene inserita all'interno
di un ciclo per scorrere le righe risultanti dall'interrogazione e 
gestire i dati contenuti.

		while ($row = $q->GetNextRecord()) {
			echo "<br>Row: ";
			foreach ($row as &$item) {
				echo $item . " - ";
			}
		}

Nel momento stesso in cui non ci sono più elementi nell'arrat $row il 
ciclo termina.

In caso la query fallisca viene restituito il valore "False", che può 
essere utilizzato per gestire il fallimento senza emettere messaggi di
debug a video. 

	} else {
		echo "<br>Query fallita<br>";
	}

A questo punto non resta altro che chiudere la query.

	$q->Close();

E' importante ricordarsi quest'ultimo passaggio per ottimizzare le 
risorse di sistema perchè in questo modo si libera la memoria usata per 
eseguire la query.

Nella versione attuale manca il controllo dei valori del filtro, che 
verrà inserita sicuramente dalla versione successiva (Il metodo preposto
è già presente ma non utilizzato in modo automatico).

*************************************
****Capitolo 2 - Query di comando****
*************************************

Vediamo un esempio di una query di inserimento, in questo caso vengono 
specificati i campi, come nel caso della query di selezione ed allo 
stesso modo viene specificata la tabella su cui operare; in più vengono 
anche passati i valori da associare ai campi specificati. 
È importante che l'ordine dei valori sia lo stesso in cui sono stati
dichiarati i rispettivi campi:

		$q = new Query();
		$q->fields = array("codice", "nome", "cognome", "anno");
		$q->tables = array("tab_provami");
		$q->values = array("01", "ciccio", "panza", "2006");

Infine viene invocato il metodo DoInsert che esegue l'inserimento:

		$q->DoInsert();

Anche in questo caso è necessario chiudere l'oggetto creato:

		$q->Close();

Nella versione attuale manca il controllo dei valori in input, che verrà
inserita a partire dalla versione successiva(Il metodo preposto è già
presente ma non utilizzato).

Nel caso di una query di update si procede nello stesso modo ma viene 
usato anche il metodo filter e la query viene eseguita attraverso il 
metodo DoUpdate.
