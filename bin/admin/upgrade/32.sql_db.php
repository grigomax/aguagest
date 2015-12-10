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

echo "<h4>Inizio aggiornamento archivi versione 32</h4>\n";


$query = "CREATE TABLE IF NOT EXISTS bvfor_testacalce (
  anno varchar(4) NOT NULL DEFAULT '',
  ndoc float NOT NULL DEFAULT '0',
  datareg date NOT NULL DEFAULT '0000-00-00',
  utente varchar(6) NOT NULL DEFAULT '',
  dragsoc varchar(40) DEFAULT NULL,
  dindirizzo varchar(30) DEFAULT NULL,
  dcap varchar(5) DEFAULT NULL,
  dcitta varchar(30) DEFAULT NULL,
  dprov char(2) DEFAULT NULL,
  dcodnazione varchar(30) DEFAULT NULL,
  modpag varchar(20) DEFAULT NULL,
  banca varchar(30) DEFAULT NULL,
  vettore varchar(40) DEFAULT NULL,
  scontofattura float(10,2) DEFAULT '0.00',
  spesevarie float(10,2) DEFAULT '0.00',
  porto varchar(10) DEFAULT NULL,
  aspetto varchar(40) DEFAULT NULL,
  status varchar(10) NOT NULL DEFAULT '',
  note text CHARACTER SET latin1,
  colli int(4) DEFAULT '0',
  pesotot float(10,2) DEFAULT '0.00',
  trasporto float(10,2) DEFAULT '0.00',
  totimpo float(16,2) DEFAULT '0.00',
  totiva float(16,2) DEFAULT '0.00',
  totdoc float(16,2) DEFAULT '0.00',
  tdocevaso varchar(30) DEFAULT NULL,
  evasonum varchar(6) DEFAULT '',
  evasoanno varchar(4) DEFAULT '',
  causale varchar(20) DEFAULT NULL,
  invio char(2) DEFAULT NULL,
  id_collo varchar(20) DEFAULT NULL,
  ts timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (anno,ndoc)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";


$conn->exec($query);


if ($conn->errorCode() == "00000") // ... tutto ok
{
	echo "inserimento nuova tabella bvfor_testtacalce punto 1 agg 32.........  perfettamente <br>";
	$_num++;
	$fine = 1;
}
else
{
	echo "inserimento nuova tabella bvfor_testacalce punto 1 agg 32 database $db_nomedb<br>";

	$errorinfo = $conn->errorInfo();
	echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
	$_errori['descrizione'] = $errorinfo[2];
	$_errori['files'] = "32.sql.db.php";
	scrittura_errori($_cosa, $_percorso, $_errori);
	echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
	echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
	echo "</body></html>";
	$fine = 0;
	exit;
}


//inserimento secondo db..


$query = "CREATE TABLE IF NOT EXISTS bvfor_dettaglio (
  anno varchar(4) NOT NULL DEFAULT '',
  ndoc float NOT NULL DEFAULT '0',
  rigo decimal(4,1) NOT NULL DEFAULT '0.0',
  utente varchar(6) DEFAULT NULL,
  articolo varchar(15) DEFAULT NULL,
  descrizione varchar(80) DEFAULT NULL,
  unita char(3) DEFAULT NULL,
  quantita float(16,2) DEFAULT '0.00',
  listino float(16,2) DEFAULT '0.00',
  scva float DEFAULT '0',
  scvb float DEFAULT '0',
  scvc float DEFAULT '0',
  nettovendita float(16,2) DEFAULT '0.00',
  totriga float(16,2) DEFAULT '0.00',
  iva char(3) DEFAULT NULL,
  totrigaprovv float(10,2) DEFAULT NULL,
  peso float(10,3) DEFAULT '0.000',
  qtaevasa float(16,2) DEFAULT '0.00',
  ts timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ";



$conn->exec($query);


if ($conn->errorCode() == "00000") // ... tutto ok
{
	echo "inserimento nuova tabella bvfor_dettaglio punto 2 agg 32.........  perfettamente <br>";
	$_num++;
	$fine = 1;
}
else
{
	echo "inserimento nuova tabella bvfor_dettaglio layout punto 2 agg 32 database $db_nomedb<br>";

	$errorinfo = $conn->errorInfo();
	echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
	$_errori['descrizione'] = $errorinfo[2];
	$_errori['files'] = "32.sql.db.php";
	scrittura_errori($_cosa, $_percorso, $_errori);
	echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
	echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
	echo "</body></html>";
	$fine = 0;
	exit;
}





//inserimento dati in stampe..

$query = "INSERT INTO stampe_layout (tdoc, ST_NDOC, ST_LOGOG, ST_LOGOM, ST_LOGOP, ST_TLOGO, ST_FONTOLOGO, ST_FONTLOGOSIZE, ST_TIPOTESTATA, ST_SOTTOTESTATA, ST_FONTINTEST, 
ST_FONTINTESTSIZE, ST_TIPOCALCE, ST_FONTESTACALCE, ST_FONTESTASIZE, ST_FONTCORPO, ST_FONTCORPOSIZE, ST_RPP, ST_RIGA, ST_RIGA_LC, ST_ARTICOLO, ST_ARTICOLO_ALL, ST_ARTICOLO_CT,
ST_ARTICOLO_LC, ST_ARTFOR, ST_ARTFOR_ALL, ST_ARTFOR_CT, ST_ARTFOR_LC, ST_DESCRIZIONE, ST_DESCRIZIONE_ALL, ST_DESCRIZIONE_CT, ST_DESCRIZIONE_LC, ST_UNITA, ST_UNITA_ALL, ST_UNITA_LC,
ST_QUANTITA, ST_QUANTITA_ALL, ST_QUANTITA_CT, ST_QUANTITA_LC, ST_QTAEVASA, ST_QTAEVASA_ALL, ST_QTAEVASA_CT, ST_QTAEVASA_LC, ST_QTAESTRATTA, ST_QTAESTRATTA_ALL, 
ST_QTAESTRATTA_CT, ST_QTAESTRATTA_LC, ST_QTASALDO, ST_QTASALDO_ALL, ST_QTASALDO_CT, ST_QTASALDO_LC, ST_LISTINO, ST_LISTINO_ALL, ST_LISTINO_CT, ST_LISTINO_LC, ST_AVV_PN, ST_SCONTI,
ST_SCONTI_ALL, ST_SCONTI_LC, ST_NETTO, ST_NETTO_ALL, ST_NETTO_CT, ST_NETTO_LC, ST_TOTRIGA, ST_TOTRIGA_ALL, ST_TOTRIGA_CT, ST_TOTRIGA_LC, ST_CODIVA, ST_CODIVA_ALL, ST_CODIVA_LC,
ST_RSALDO, ST_RSALDO_ALL, ST_RSALDO_LC, ST_PESO, ST_PESO_ALL, ST_PESO_LC, ST_CONSEGNA, ST_CONSEGNA_ALL, ST_CONSEGNA_CT, ST_CONSEGNA_LC, ST_AVVISO, ST_AVVISO_ALL, ST_AVVISO_LC, ST_PREZZI, ST_DATA, BODY) VALUES
('ddt_diretto', 'D.D.T. DPR 476/96', 'intestazione.jpg', '', '', 1, '', '11', 1, 1, 'Arial', '13', 1, 'Arial', '10', 'Arial', '10', 25, 'NO', 0, 'SI', 'CENTER', 8, 12, 'NO', 'CENTER', 8, 0, 'SI', '', 55, 63, 'SI', '', 5, 'SI', 'CENTER', 0, 10, 'NO', '', 0, 0, 'NO', '', 0, 0, 'NO', '', 0, 0, 'SI', 'CENTER', 0, 10, 'SI', 'NO', '', 0, '', '', 0, 0, 'NO', '', 0, 0, 'NO', '', 0, 'NO', '', 0, 'NO', '', 0, 'NO', '', 0, 0, 'NO', '', 'ATTENZIONE da marzo sara in vigore il nuovo listino 2012', 'SI', 'SI', 'Buon giorno.,.\r\n\r\nIn allegato trovate il d.d.t da voi richiesto\r\n\r\nSaluti\r')";




$conn->query($query);


if ($conn->errorCode() == "00000") // ... tutto ok
{
	echo "Inserimento dati tabella layout 3 agg. 32.........  perfettamente <br>";
	$_num++;
	$fine = 1;
}
else
{
	echo "Errore nell'inserimento dati tabella layout punto 3 agg 32 database $db_nomedb<br>";
	echo "query = $query<br>\n";

	$errorinfo = $conn->errorInfo();
	echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
	$_errori['descrizione'] = $errorinfo[2];
	$_errori['files'] = "32.sql.db.php";
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