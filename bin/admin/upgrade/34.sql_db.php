<?php

session_start;
require "../../../setting/vars.php";
$_percorso = "../../";
require_once $_percorso . "librerie/lib_html.php";

//connettiamoci al database
$conn = connessione_mysql("PDO");


/* AGGIORNAMENTO TABELLE DATABASE AL 10/03/2014
  Eliminazione archivio aaa
 * Aggiunta archivio codici_barre per gestione codici a barre

 */

echo "<h4>Inizio aggiornamento archivi versione 34</h4>\n";


//eliminazione archivio aaa

$query = "DROP TABLE IF EXISTS aaa ";

$conn->exec($query);

if ($conn->errorCode() == "00000") // ... tutto ok
{
	echo "Eliminazione archivio aaa agg. 34 punto 1.........  riuscita perfettamente <br>";
	$_num++;
	$fine = 1;
}
else
{
	echo "Errore eliminazione archivio aaa agg. 34 punto 1 $db_nomedb<br>";

	$errorinfo = $conn->errorInfo();
	echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
	$_errori['descrizione'] = $errorinfo[2];
	$_errori['files'] = "34.sql.db.php";
	scrittura_errori($_cosa, $_percorso, $_errori);
	echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
	echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
	echo "</body></html>";
	$fine = 0;
	exit;
}


$query = "DROP TABLE IF EXISTS barcode ";

$conn->exec($query);

if ($conn->errorCode() == "00000") // ... tutto ok
{
	echo "Eliminazione archivio barcode agg. 34 punto 1/2.........  riuscita perfettamente <br>";
	$_num++;
	$fine = 1;
}
else
{
	echo "Errore eliminazione archivio barcode agg. 34 punto 1/2 $db_nomedb<br>";

	$errorinfo = $conn->errorInfo();
	echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
	$_errori['descrizione'] = $errorinfo[2];
	$_errori['files'] = "34.sql.db.php";
	scrittura_errori($_cosa, $_percorso, $_errori);
	echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
	echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
	echo "</body></html>";
	$fine = 0;
	exit;
}

// creo la tabella codici_barre

$query = "CREATE TABLE barcode (codbar VARCHAR(20) NOT NULL ,articolo VARCHAR(15) NOT NULL , rigo INT(3) NOT NULL DEFAULT 1 , PRIMARY KEY (codbar)) ENGINE=MyISAM DEFAULT CHARSET=utf8";


$conn->exec($query);

if ($conn->errorCode() == "00000") // ... tutto ok
{
	echo "Creazione tabella barcode  35 punto 2.........  riuscita perfettamente <br>";
	$_num++;
	$fine = 1;
}
else
{
	echo "Creazione tabella barcode agg. 34 punto 2 $db_nomedb<br>";

	$errorinfo = $conn->errorInfo();
	echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
	$_errori['descrizione'] = $errorinfo[2];
	$_errori['files'] = "34.sql.db.php";
	scrittura_errori($_cosa, $_percorso, $_errori);
	echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
	echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
	echo "</body></html>";
	$fine = 0;
	exit;
}



//ora dobbiamo traslocare tutti i codici a barre da quelli nuovi ai vechi più aggiungere quelli nuovi.
//La procedura che conviene fare è quella di leggere tutti gli articoli ed i codici a barre esistenti ed trasferirli nel database..
//poi alla fine eliminiamo il tutto.

$query = "SELECT articolo, codbar FROM articoli ORDER BY articolo";

$result = $conn->query($query);

if ($conn->errorCode() == "00000") // ... tutto ok
{
	echo "lettura articoli .........  agg, 34 punto 3 riuscita perfettamente <br>";
	$_num++;
	$fine = 1;
}
else
{
	echo "Errore lettura articoli agg. 34 punto 3 $db_nomedb<br>";

	$errorinfo = $conn->errorInfo();
	echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
	$_errori['descrizione'] = $errorinfo[2];
	$_errori['files'] = "34.sql.db.php";
	scrittura_errori($_cosa, $_percorso, $_errori);
	echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
	echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
	echo "</body></html>";
	$fine = 0;
	exit;
}


// con un ciclo di for inserisco

foreach ($result AS $dati)
{

	//ora inseriamo tutti gli articoli nel nuovo database..

	$query = "INSERT INTO barcode (codbar, articolo, rigo) VALUES ('$dati[articolo]', '$dati[articolo]', '1')";

	$result = $conn->query($query);

	if ($conn->errorCode() == "00000") // ... tutto ok
	{
		#echo "Inserimento codice a barre articolo $dati[articolo] riuscita perfettamente <br>";
		$_num++;
		$fine = 1;
	}
	else
	{
		echo "Errore Inserimento codice a barre articolo $dati[articolo] $db_nomedb<br>";

		$errorinfo = $conn->errorInfo();
		echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
		$_errori['descrizione'] = $errorinfo[2];
		$_errori['files'] = "34.sql.db.php";
		scrittura_errori($_cosa, $_percorso, $_errori);
		echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
		echo "Oppure comunicare il file agua_log presente nella directory spool<br>Errore non bloccante.. continuo.. <br>";
		$fine = 0;
		
	}

	//verifichiamo se il codice a barre memorizzato all'interno del database è diverso se si lo inserisco come numeto 2

	if ($dati['codbar'] != $dati['articolo'])
	{
		//ecco inseriamo anche il codice associato come 2
		$query = "INSERT INTO barcode (codbar, articolo, rigo) VALUES ('$dati[codbar]', '$dati[articolo]', '2')";

		$result = $conn->query($query);

		if ($conn->errorCode() == "00000") // ... tutto ok
		{
			#echo "Inserimento secondo codice a barre $dati[codbar] articolo $dati[articolo] riuscita perfettamente <br>";
			$_num++;
			$fine = 1;
		}
		else
		{
			echo "Errore Inserimento codice a barre 2 articolo $dati[articolo] $db_nomedb<br>";

			$errorinfo = $conn->errorInfo();
			echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
			$_errori['descrizione'] = $errorinfo[2];
			$_errori['files'] = "34.sql.db.php";
			scrittura_errori($_cosa, $_percorso, $_errori);
			echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
			echo "Oppure comunicare il file agua_log presente nella directory spool<br>Errore non bloccante la procedura continuo.. <br>\n";
			echo "</body></html>";
			$fine = 0;
			
		}
	}
}


echo "<br><br>Eventuali errori non bloccanti non costituiscono un problema..<br> Se si vuole comunicare all'aministratore il file log.. ";


echo "eliminazione Vecchi codici... <br>";


$query = "UPDATE articoli SET codbar = '' ";

$conn->exec($query);

if ($conn->errorCode() == "00000") // ... tutto ok
{
	echo "Eliminazione vecchi codici agg. 34 punto 4.........  riuscita perfettamente <br>";
	$_num++;
	$fine = 1;
}
else
{
	echo "Errore eliminazione vecchi codici agg. 34 punto 4 $db_nomedb<br>";

	$errorinfo = $conn->errorInfo();
	echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
	$_errori['descrizione'] = $errorinfo[2];
	$_errori['files'] = "34.sql.db.php";
	scrittura_errori($_cosa, $_percorso, $_errori);
	echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
	echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
	echo "</body></html>";
	$fine = 0;
	exit;
}




//------------------------------------------------------------------------------
//    Verifico che non ci siano stati errori
if ($fine != "1")
{
#blocco il programma ancor prima di iniziare..
	echo "Impossibile continuare con il programma causa errori <br>";
	echo "Si richiede assistenza Ricopiare o stampare questa pagina <br>\n";
	echo "Errore File update archivi n. 34 <br>\n";
	exit;
}
else
{

	echo "Aggiornamento tabella versione..... 34 del 10/03/2014 COMPLETATO !<br>";

	$_res = $conn->query("UPDATE version SET aguabase='$AGUABASE'");

	//echo 'Count rows selected: ' . $_res->rowCount();
	if ($_res->rowCount() >= 0)
	{
		echo "Primo aggiornamento eseguito archivi <br>... \n";
		$fine = 1;
	}
	else
	{
		echo "Primo aggiornamento archivi Non eseguito<br>... \n";
		//scrivo gli errori
		$errorinfo = $conn->errorInfo();
		$_errori['descrizione'] = $errorinfo[2];
		$_errori['files'] = "aggiorna.php";
		scrittura_errori($_cosa, $_percorso, $_errori);

		$fine = 0;
	}
}
?>