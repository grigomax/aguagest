<?php

session_start;
require "../../../setting/vars.php";
$_percorso = "../../";
require_once $_percorso . "librerie/lib_html.php";

//connettiamoci al database
$conn = connessione_mysql("PDO");


/* AGGIORNAMENTO TABELLE DATABASE AL 08/07/2013
  Inserimento di un'altro archivio per i ddt dretti del fornitore

 */

echo "<h4>Inizio aggiornamento archivi versione 33</h4>\n";


$query = "ALTER TABLE fornitori CHANGE scontocli spesometro CHAR( 2 ) NULL DEFAULT 'NO'";


$conn->exec($query);


if ($conn->errorCode() == "00000") // ... tutto ok
{
	echo "Aggiornamento tabella fornitori punto 1 agg 33.........  perfettamente <br>";
	$_num++;
	$fine = 1;
}
else
{
	echo "Aggiornamento tabella fornitori database punto 1 agg. 33 $db_nomedb<br>";

	$errorinfo = $conn->errorInfo();
	echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
	$_errori['descrizione'] = $errorinfo[2];
	$_errori['files'] = "33.sql.db.php";
	scrittura_errori($_cosa, $_percorso, $_errori);
	echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
	echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
	echo "</body></html>";
	$fine = 0;
	exit;
}

$query = "UPDATE fornitori SET spesometro = 'NO'";


$conn->exec($query);


if ($conn->errorCode() == "00000") // ... tutto ok
{
	echo "Aggiornamento tabella fornitori punto 2 agg 33.........  perfettamente <br>";
	$_num++;
	$fine = 1;
}
else
{
	echo "Aggiornamento tabella fornitori database punto 2 agg. 33 $db_nomedb<br>";

	$errorinfo = $conn->errorInfo();
	echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
	$_errori['descrizione'] = $errorinfo[2];
	$_errori['files'] = "33.sql.db.php";
	scrittura_errori($_cosa, $_percorso, $_errori);
	echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
	echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
	echo "</body></html>";
	$fine = 0;
	exit;
}


$query = "UPDATE prima_nota SET sp_metro = 'NO' where sp_metro != 'SI' ";


$conn->query($query);


if ($conn->errorCode() == "00000") // ... tutto ok
{
	echo "Aggiornamento tabella prima_nota punto 3 agg 33.........  perfettamente <br>";
	$_num++;
	$fine = 1;
}
else
{
	echo "Aggiornamento tabella prima_nota database punto 3 agg. 33 $db_nomedb<br>";

	$errorinfo = $conn->errorInfo();
	echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
	$_errori['descrizione'] = $errorinfo[2];
	$_errori['files'] = "33.sql.db.php";
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
	echo "Errore File update archivi n. 32 <br>\n";
	exit;
}
else
{

	echo "Aggiornamento tabella versione..... 32 del 12/07/2013 COMPLETATO !<br>";

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