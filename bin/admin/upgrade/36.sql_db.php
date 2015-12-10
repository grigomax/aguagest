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

echo "<h4>Inizio aggiornamento archivi versione 36</h4>\n";


//eliminazione archivio aaa

$query = "ALTER TABLE of_dettaglio CHANGE qtaevasa qtaevasa FLOAT( 16, 2 ) NULL DEFAULT '0.00',
CHANGE qtaestratta qtaestratta FLOAT( 16, 2 ) NULL DEFAULT '0.00',
CHANGE qtasaldo qtasaldo FLOAT( 16, 2 ) NULL DEFAULT '0.00' ";

$conn->exec($query);

if ($conn->errorCode() == "00000") // ... tutto ok
{
	echo "Alterazione tabella of_dettaglio agg. 36 punto 1.........  riuscita perfettamente <br>";
	$_num++;
	$fine = 1;
}
else
{
	echo "Alterazione tabella of_dettaglio agg. 36 punto 1 $db_nomedb<br>";

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


//aggiungiamo la data di scadenza..

$query = "ALTER TABLE pv_testacalce ADD data_scad DATE NULL DEFAULT '0000-00-00' AFTER invio";

$conn->exec($query);

if ($conn->errorCode() == "00000") // ... tutto ok
{
	echo "Alterazione tabella pv_testacalce agg. 36 punto 2.........  riuscita perfettamente <br>";
	$_num++;
	$fine = 1;
}
else
{
	echo "Alterazione tabella pv_testacalce agg. 36 punto 2 $db_nomedb<br>";

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
	echo "Errore File update archivi n. 36 <br>\n";
	exit;
}
else
{

	echo "Aggiornamento tabella versione..... 36 del 30/05/2014 COMPLETATO !<br>";

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