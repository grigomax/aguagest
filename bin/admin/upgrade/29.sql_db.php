<?php

session_start;
require "../../../setting/vars.php";
$_percorso = "../../";
require_once $_percorso . "librerie/lib_html.php";

//connettiamoci al database
$conn = connessione_mysql("PDO");

echo "ciao";

/* AGGIORNAMENTO TABELLE DATABASE AL 05/04/2013
  Aggiornamento 29 campio tipologia di allocazione tabelle da SWEDISW a UTF8

 */

//selezioniamo tutte le tabelle e foi con un ciclo di for le aggiorniamo tutte..


$_res = $conn->query("SHOW TABLE STATUS FROM $db_nomedb");
echo 'Tabelle trovate: ' . $_res->rowCount() . "<br/>\n";
if ($_res->rowCount() > 0)
{
	foreach ($_res->fetchAll() as $row)
	{
		//qui dentro cambiamo tutte le tabelle in un sol colpo..

		$conn->exec("ALTER TABLE $row[Name] DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");

		if ($conn->errorCode() == "00000") // ... tutto ok
		{
			echo "Alterazione tabella $row[name] punto $_num agg 29.........  perfettamente <br>";
			$_num++;
			$fine = 1;
		}
		else
		{
			echo "Errore nell'Alterazione tabella $row[name] punto $_num agg 29 database $db_nomedb";

			$errorinfo = $conn->errorInfo();
			echo $errorinfo[2] . "<br/>"; // stringa con l' errore
			$_errori['descrizione'] = $errorinfo[2];
			$_errori['files'] = "29.sql.db.php";
			scrittura_errori($_cosa, $_percorso, $_errori);
			echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
			echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
			echo "</body></html>";
			$fine = 0;
			exit;
		}
	}
}
else
{
	echo "Errore nessuna tabella trovata database $db_nomedb";
	$errorinfo = $conn->errorInfo();
	echo $errorinfo[2] . "<br/>"; // stringa con l' errore
	$_errori['descrizione'] = $errorinfo[2];
	$_errori['files'] = "29.sql.db.php";
	scrittura_errori($_cosa, $_percorso, $_errori);
	echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
	echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
	echo "</body></html>";
	$fine = 0;
	exit;
}


//aggiorniamo il database zone e lo postiamo a Myisam;


$conn->exec("ALTER TABLE zone ENGINE = MyISAM");


if ($conn->errorCode() == "00000") // ... tutto ok
{
	echo "Alterazione tabella Zone punto $_num agg 29.........  perfettamente <br>";
	$_num++;
	$fine = 1;
}
else
{
	echo "Errore nell'Alterazione tabella Zone punto $_num agg 29 database $db_nomedb";

	$errorinfo = $conn->errorInfo();
	echo $errorinfo[2] . "<br/>"; // stringa con l' errore
	$_errori['descrizione'] = $errorinfo[2];
	$_errori['files'] = "29.sql.db.php";
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

	echo "Aggiornamento tabella versione..... 29 del 05/04/2013 COMPLETATO !<br>";

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