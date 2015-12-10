<?php

session_start;
require "../../../setting/vars.php";
$_percorso = "../../";
require_once $_percorso . "librerie/lib_html.php";

//connettiamoci al database
$conn = connessione_mysql("PDO");


/* AGGIORNAMENTO TABELLE DATABASE AL 15/12/2015
 * Aggiornamento tabelle utenti
 * aggiornamento tabella effetti
 * aggiornamento tabelle documenti con inserimento suffisso di numertazione
 * inserimento nuova tabella destinazione merce
 * Nuova tabella destinazioni
 * 
 */

echo "<h4>Inizio aggiornamento archivi versione 50</h4>\n";

$_versione = "49";
$_punto = "0";



$query = "ALTER TABLE effetti ADD ts TIMESTAMP NOT NULL";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella effetti descrizione. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agg. $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}

$query = "ALTER TABLE utenti ADD USER_SCREEN_COLOR_BACKGROUND CHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER datareg , ADD USER_SCREEN_WIDTH CHAR( 4 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '100' AFTER USER_SCREEN_COLOR_BACKGROUND, ADD USER_SCREEN_FONT_TYPE CHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER USER_SCREEN_WIDTH , ADD USER_SCREEN_FONT_SIZE CHAR( 5 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER USER_SCREEN_FONT_TYPE";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella effetti descrizione. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agg. $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}



$query = "CREATE TABLE IF NOT EXISTS destinazioni (
  utente varchar(6) NOT NULL DEFAULT '',
  codice int(2) NOT NULL DEFAULT '1',
  datareg date NOT NULL DEFAULT '0000-00-00',
  dragsoc varchar(40) DEFAULT NULL,
  dragsoc2 varchar(60) DEFAULT NULL,
  dindirizzo varchar(30) DEFAULT NULL,
  dcap varchar(5) DEFAULT NULL,
  dcitta varchar(30) DEFAULT NULL,
  dprov char(2) DEFAULT NULL,
  dcodnazione varchar(30) DEFAULT NULL,
  telefonodest varchar(20) DEFAULT NULL,
  faxdest varchar(20) DEFAULT '',
  demail varchar(60) DEFAULT NULL,
  dcontatto varchar(60) DEFAULT NULL,
  predefinito tinyint(1) NOT NULL DEFAULT '0',
  ts timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (utente,codice)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";


$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}




$query = "ALTER TABLE fv_testacalce ADD suffix CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'A' AFTER anno";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}


$query = "ALTER TABLE fv_dettaglio ADD suffix CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'A' AFTER anno";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}


$query = "ALTER TABLE agua.fv_testacalce DROP PRIMARY KEY, ADD PRIMARY KEY ( tdoc, anno, suffix, ndoc )";
$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}
//fine fattura

//modifica ddt

$query = "ALTER TABLE bv_bolle ADD suffix CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'A' AFTER anno";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}


$query = "ALTER TABLE bv_dettaglio ADD suffix CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'A' AFTER anno";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}


$query = "ALTER TABLE agua.bv_bolle DROP PRIMARY KEY, ADD PRIMARY KEY ( anno, suffix, ndoc )";
$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}

//aggiungiamo anche il suffisso per i documenti evasi..

$query = "ALTER TABLE bv_bolle ADD evasosuffix CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' AFTER evasoanno";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}
//fine bolle..

//inizio conferme ordine..
$query = "ALTER TABLE co_testacalce ADD suffix CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'A' AFTER anno";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}


$query = "ALTER TABLE co_dettaglio ADD suffix CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'A' AFTER anno";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}


$query = "ALTER TABLE agua.co_testacalce DROP PRIMARY KEY, ADD PRIMARY KEY ( anno, suffix, ndoc )";
$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}

//aggiungiamo anche il suffisso per i documenti evasi..

$query = "ALTER TABLE co_testacalce ADD evasosuffix CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' AFTER evasoanno";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}
//fine conferme

//inizio ordine clienti..
$query = "ALTER TABLE oc_testacalce ADD suffix CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'A' AFTER anno";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}


$query = "ALTER TABLE oc_dettaglio ADD suffix CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'A' AFTER anno";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}


$query = "ALTER TABLE agua.oc_testacalce DROP PRIMARY KEY, ADD PRIMARY KEY ( anno, suffix, ndoc )";
$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}

//aggiungiamo anche il suffisso per i documenti evasi..

$query = "ALTER TABLE oc_testacalce ADD evasosuffix CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' AFTER evasoanno";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}
//fine conferme ordine 

//inizio ordini fornitore
$query = "ALTER TABLE of_testacalce ADD suffix CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'A' AFTER anno";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}


$query = "ALTER TABLE of_dettaglio ADD suffix CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'A' AFTER anno";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}


$query = "ALTER TABLE agua.of_testacalce DROP PRIMARY KEY, ADD PRIMARY KEY ( anno, suffix, ndoc )";
$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}

//aggiungiamo anche il suffisso per i documenti evasi..

$query = "ALTER TABLE of_testacalce ADD evasosuffix CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' AFTER evasoanno";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}
// fine ordini fornitore

// inizio preventivi..
$query = "ALTER TABLE pv_testacalce ADD suffix CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'A' AFTER anno";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}


$query = "ALTER TABLE pv_dettaglio ADD suffix CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'A' AFTER anno";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}


$query = "ALTER TABLE agua.pv_testacalce DROP PRIMARY KEY, ADD PRIMARY KEY ( anno, suffix, ndoc )";
$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}

//aggiungiamo anche il suffisso per i documenti evasi..

$query = "ALTER TABLE pv_testacalce ADD evasosuffix CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' AFTER evasoanno";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}
// fine preventivi..

//inizio doc_basket

$query = "ALTER TABLE doc_basket ADD suffix CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' AFTER anno";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}

$query = "ALTER TABLE agua.doc_basket DROP PRIMARY KEY, ADD PRIMARY KEY ( sessionid, rigo, anno, suffix, ndoc )";
$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}

//fine bascket

//inizio tabella effetti
$query = "ALTER TABLE effetti ADD suffixdoc CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' AFTER annodoc";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}


//fine tabella effetti

//inizio tabella magastorico
$query = "ALTER TABLE magastorico ADD suffix CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' AFTER anno";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}


//fine tabella magastorico

//inizio tabella magazzino

$query = "ALTER TABLE magazzino ADD suffix CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' AFTER anno";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}

//fine tabella magazzino

//inizio tabella provvigioni
$query = "ALTER TABLE provvigioni ADD suffix CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' AFTER anno";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}
//fine tabella provvigioni

//inizio tabella prima nota
$query = "ALTER TABLE prima_nota ADD suffix_doc CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' AFTER anno_doc";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}

$query = "ALTER TABLE prima_nota ADD suffix_proto CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' AFTER anno_proto";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}
//fine tabella prima_nota

//inizio tabella prima nota baskket
$query = "ALTER TABLE prima_nota_basket ADD suffix_doc CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' AFTER anno_doc";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}

$query = "ALTER TABLE prima_nota_basket ADD suffix_proto CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' AFTER anno_proto";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}
// fine prima nota basket

//inizio tabella scadenziario..
$query = "ALTER TABLE scadenziario ADD suffix_proto CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' AFTER anno_proto";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Aggiornamento archivi. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Aggiornamento archivi $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "$_versione.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}



//fine tabella scadenziario




//fine aggiornamenti............................................
//    Verifico che non ci siano stati errori
if ($fine != "1")
{
#blocco il programma ancor prima di iniziare..
    echo "Impossibile continuare con il programma causa errori <br>";
    echo "Si richiede assistenza Ricopiare o stampare questa pagina <br>\n";
    echo "Errore File update archivi n. $_versione <br>\n";
    exit;
}
else
{

    echo "Aggiornamento tabella versione..... $_versione del 05/09/2015 COMPLETATO !<br>";

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