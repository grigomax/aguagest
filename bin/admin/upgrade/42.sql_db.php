<?php

session_start;
require "../../../setting/vars.php";
$_percorso = "../../";
require_once $_percorso . "librerie/lib_html.php";

//connettiamoci al database
$conn = connessione_mysql("PDO", $query, $_parametri);


/* AGGIORNAMENTO TABELLE DATABASE AL 12/12/2014
  Aggiornamento tabella utenti ed inserimento nuovo arcivio banned_ip
 */

echo "<h4>Inizio aggiornamento archivi versione 42</h4>\n";

$query = "ALTER TABLE stampe_layout ADD ST_INTERLINEA INT( 1 ) NOT NULL DEFAULT '0' AFTER BODY";

$conn->exec($query);

if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella utenti agg. 41 punto 3.........  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agg. 41 punto 3 $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "41.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}






$query = "INSERT INTO stampe_layout (tdoc, ST_NDOC, ST_LOGOG, ST_LOGOM, ST_LOGOP, ST_TLOGO, ST_FONTOLOGO, ST_FONTLOGOSIZE, ST_TIPOTESTATA, ST_SOTTOTESTATA, ST_FONTINTEST, ST_FONTINTESTSIZE, ST_TIPOCALCE, ST_FONTESTACALCE, ST_FONTESTASIZE, ST_FONTCORPO, ST_FONTCORPOSIZE, ST_RPP, ST_RIGA, ST_RIGA_LC, ST_ARTICOLO, ST_ARTICOLO_ALL, ST_ARTICOLO_CT, ST_ARTICOLO_LC, ST_ARTFOR, ST_ARTFOR_ALL, ST_ARTFOR_CT, ST_ARTFOR_LC, ST_DESCRIZIONE, ST_DESCRIZIONE_ALL, ST_DESCRIZIONE_CT, ST_DESCRIZIONE_LC, ST_UNITA, ST_UNITA_ALL, ST_UNITA_LC, ST_QUANTITA, ST_QUANTITA_ALL, ST_QUANTITA_CT, ST_QUANTITA_LC, ST_QTAEVASA, ST_QTAEVASA_ALL, ST_QTAEVASA_CT, ST_QTAEVASA_LC, ST_QTAESTRATTA, ST_QTAESTRATTA_ALL, ST_QTAESTRATTA_CT, ST_QTAESTRATTA_LC, ST_QTASALDO, ST_QTASALDO_ALL, ST_QTASALDO_CT, ST_QTASALDO_LC, ST_LISTINO, ST_LISTINO_ALL, ST_LISTINO_CT, ST_LISTINO_LC, ST_AVV_PN, ST_SCONTI, ST_SCONTI_ALL, ST_SCONTI_LC, ST_NETTO, ST_NETTO_ALL, ST_NETTO_CT, ST_NETTO_LC, ST_TOTRIGA, ST_TOTRIGA_ALL, ST_TOTRIGA_CT, ST_TOTRIGA_LC, ST_CODIVA, ST_CODIVA_ALL, ST_CODIVA_LC, ST_RSALDO, ST_RSALDO_ALL, ST_RSALDO_LC, ST_PESO, ST_PESO_ALL, ST_PESO_LC, ST_CONSEGNA, ST_CONSEGNA_ALL, ST_CONSEGNA_CT, ST_CONSEGNA_LC, ST_AVVISO, ST_AVVISO_ALL, ST_AVVISO_LC, ST_PREZZI, ST_DATA, BODY, ST_INTERLINEA) VALUES
('eti_clienti', 'Etichetta clienti', 'logocat.png', '', '', 1, '', '', 0, 0, '', '', 0, '', '', 'Arial', '14', 1, 'NO', 700, 'SI', 'LEFT', 10, 10, 'NO', '', 0, 0, 'SI', 'LEFT', 80, 10, '', '', 0, 'SI', 'LEFT', 0, 5, 'SI', 'LEFT', 0, 50, 'SI', '', 0, 20, 'SI', 'LEFT', 0, 10, '', '', 0, 0, '', '', '', 0, '', '', 0, 0, '', '', 0, 0, '', '', 0, '', '', 0, '', '', 0, '', '', 0, 0, 'SI', '', 'ciao', '', '', '', 2)";

$conn->exec($query);

if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Inserita etichetta clienti agg. 42 punto 1.........  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agg. 42 punto 1 $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "41.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}

$query = "INSERT INTO stampe_layout (tdoc, ST_NDOC, ST_LOGOG, ST_LOGOM, ST_LOGOP, ST_TLOGO, ST_FONTOLOGO, ST_FONTLOGOSIZE, ST_TIPOTESTATA, ST_SOTTOTESTATA, ST_FONTINTEST, ST_FONTINTESTSIZE, ST_TIPOCALCE, ST_FONTESTACALCE, ST_FONTESTASIZE, ST_FONTCORPO, ST_FONTCORPOSIZE, ST_RPP, ST_RIGA, ST_RIGA_LC, ST_ARTICOLO, ST_ARTICOLO_ALL, ST_ARTICOLO_CT, ST_ARTICOLO_LC, ST_ARTFOR, ST_ARTFOR_ALL, ST_ARTFOR_CT, ST_ARTFOR_LC, ST_DESCRIZIONE, ST_DESCRIZIONE_ALL, ST_DESCRIZIONE_CT, ST_DESCRIZIONE_LC, ST_UNITA, ST_UNITA_ALL, ST_UNITA_LC, ST_QUANTITA, ST_QUANTITA_ALL, ST_QUANTITA_CT, ST_QUANTITA_LC, ST_QTAEVASA, ST_QTAEVASA_ALL, ST_QTAEVASA_CT, ST_QTAEVASA_LC, ST_QTAESTRATTA, ST_QTAESTRATTA_ALL, ST_QTAESTRATTA_CT, ST_QTAESTRATTA_LC, ST_QTASALDO, ST_QTASALDO_ALL, ST_QTASALDO_CT, ST_QTASALDO_LC, ST_LISTINO, ST_LISTINO_ALL, ST_LISTINO_CT, ST_LISTINO_LC, ST_AVV_PN, ST_SCONTI, ST_SCONTI_ALL, ST_SCONTI_LC, ST_NETTO, ST_NETTO_ALL, ST_NETTO_CT, ST_NETTO_LC, ST_TOTRIGA, ST_TOTRIGA_ALL, ST_TOTRIGA_CT, ST_TOTRIGA_LC, ST_CODIVA, ST_CODIVA_ALL, ST_CODIVA_LC, ST_RSALDO, ST_RSALDO_ALL, ST_RSALDO_LC, ST_PESO, ST_PESO_ALL, ST_PESO_LC, ST_CONSEGNA, ST_CONSEGNA_ALL, ST_CONSEGNA_CT, ST_CONSEGNA_LC, ST_AVVISO, ST_AVVISO_ALL, ST_AVVISO_LC, ST_PREZZI, ST_DATA, BODY, ST_INTERLINEA) VALUES
('eti_fornitori', 'Etichetta fornitori', 'logocat.png', '', '', 1, '', '', 0, 0, '', '', 0, '', '', 'Arial', '14', 1, 'NO', 700, 'SI', 'LEFT', 10, 10, 'NO', '', 0, 0, 'SI', 'LEFT', 80, 10, '', '', 0, 'SI', 'LEFT', 0, 5, 'SI', 'LEFT', 0, 50, 'SI', '', 0, 20, 'SI', 'LEFT', 0, 10, '', '', 0, 0, '', '', '', 0, '', '', 0, 0, '', '', 0, 0, '', '', 0, '', '', 0, '', '', 0, '', '', 0, 0, 'SI', '', 'ciao', '', '', '', 2)";

$conn->exec($query);

if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Inserita etichetta fornitori agg. 42 punto 2.........  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agg. 42 punto 2 $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "41.sql.db.php";
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
    echo "Errore File update archivi n. 42 <br>\n";
    exit;
}
else
{

    echo "Aggiornamento tabella versione..... 42 del 08/12/2014 COMPLETATO !<br>";

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