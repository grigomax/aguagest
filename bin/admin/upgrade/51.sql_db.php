<?php

session_start;
require "../../../setting/vars.php";
$_percorso = "../../";
require_once $_percorso . "librerie/lib_html.php";

//connettiamoci al database
$conn = connessione_mysql("PDO", $query, $_parametri);


/* AGGIORNAMENTO TABELLE DATABASE AL 10/01/2016
 * Aggiornamento tabelle utenti
 * aggiornamento tabella effetti
 * aggiornamento tabelle documenti con inserimento suffisso di numertazione
 * inserimento nuova tabella destinazione merce
 * Nuova tabella destinazioni
 * 
 */

echo "<h4>Inizio aggiornamento archivi versione 51</h4>\n";

$_versione = "51";
$_punto = "0";


//cambiamo tutti i valori dei documenti..

$query = "UPDATE stampe_layout set ST_RIGA_LC = ST_RIGA_LC * 1.93, "
        . "ST_ARTICOLO_LC = ST_ARTICOLO_LC * 1.93,"
        . "ST_ARTFOR_LC = ST_ARTFOR_LC * 1.93,"
        . "ST_DESCRIZIONE_LC = ST_DESCRIZIONE_LC * 1.93,"
        . "ST_UNITA_LC = ST_UNITA_LC * 1.93,"
        . "ST_QUANTITA_LC = ST_QUANTITA_LC * 1.93,"
        . "ST_QTAEVASA_LC = ST_QTAEVASA_LC * 1.93,"
        . "ST_QTAESTRATTA_LC = ST_QTAESTRATTA_LC * 1.93,"
        . "ST_QTASALDO_LC = ST_QTASALDO_LC * 1.93,"
        . "ST_LISTINO_LC = ST_LISTINO_LC * 1.93, "
        . "ST_SCONTI_LC = ST_SCONTI_LC * 1.93,"
        . "ST_NETTO_LC = ST_NETTO_LC * 1.93,"
        . "ST_TOTRIGA_LC = ST_TOTRIGA_LC * 1.93,"
        . "ST_CODIVA_LC = ST_CODIVA_LC * 1.93,"
        . "ST_RSALDO_LC = ST_RSALDO_LC * 1.93,"
        . "ST_PESO_LC = ST_PESO_LC * 1.93,"
        . "ST_CONSEGNA_LC = ST_CONSEGNA_LC * 1.93,"
        . "ST_AVVISO_LC = ST_AVVISO_LC * 1.93";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella $_versione punto $_punto ..  riuscita perfettamente <br>";
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




$query = "ALTER TABLE catmer ADD imballo INT(1) NOT NULL DEFAULT '0' AFTER catmer, ADD ts TIMESTAMP NOT NULL AFTER imballo";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella $_versione punto $_punto ..  riuscita perfettamente <br>";
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


//inseriamo la possibilità di escludere il cliente dalla pupplicità

$query = "ALTER TABLE clienti ADD es_pubblicita CHAR(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'NO' AFTER es_selezione";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella $_versione punto $_punto ..  riuscita perfettamente <br>";
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


$query = "ALTER TABLE stampe_layout ADD PRIMARY KEY(tdoc)";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella $_versione punto $_punto ..  riuscita perfettamente <br>";
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