<?php

session_start;
require "../../../setting/vars.php";
$_percorso = "../../";
require_once $_percorso . "librerie/lib_html.php";

//connettiamoci al database
$conn = connessione_mysql("PDO");


/* AGGIORNAMENTO TABELLE DATABASE AL 12/04/2013
  Aggiornamento delle tabelle conferme ed ordini per il passaggio definitivo ai campi importanti come not null

 */

echo "<h4>Inizio aggiornamento archivi versione 30</h4>\n";

$conn->exec("ALTER TABLE of_dettaglio CHANGE rsaldo rsaldo CHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");


if ($conn->errorCode() == "00000") // ... tutto ok
{
	echo "Alterazione tabella of_dettaglio punto 1 agg 30.........  perfettamente <br>";
	$_num++;
	$fine = 1;
}
else
{
	echo "Errore nell'Alterazione tabella of_dettaglio punto 1 agg 30 database $db_nomedb";

	$errorinfo = $conn->errorInfo();
	echo $errorinfo[2] . "<br/>"; // stringa con l' errore
	$_errori['descrizione'] = $errorinfo[2];
	$_errori['files'] = "30.sql.db.php";
	scrittura_errori($_cosa, $_percorso, $_errori);
	echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
	echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
	echo "</body></html>";
	$fine = 0;
	exit;
}

$conn->exec("ALTER TABLE co_dettaglio CHANGE rsaldo rsaldo CHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");


if ($conn->errorCode() == "00000") // ... tutto ok
{
	echo "Alterazione tabella co_dettaglio punto 1 agg 30.........  perfettamente <br>";
	$_num++;
	$fine = 1;
}
else
{
	echo "Errore nell'Alterazione tabella co_dettaglio punto 1 agg 30 database $db_nomedb";

	$errorinfo = $conn->errorInfo();
	echo $errorinfo[2] . "<br/>"; // stringa con l' errore
	$_errori['descrizione'] = $errorinfo[2];
	$_errori['files'] = "30.sql.db.php";
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
	echo "Errore File update archivi n. 29 <br>\n";
	exit;
}
else
{

	echo "Aggiornamento tabella versione..... 30 del 12/04/2013 COMPLETATO !<br>";

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