<?php

session_start;
require "../../../setting/vars.php";
$_percorso = "../../";
require_once $_percorso . "librerie/lib_html.php";

//connettiamoci al database
$conn = connessione_mysql("PDO");


/* AGGIORNAMENTO TABELLE DATABASE AL 30/01/2015
  Aggiornamento tabelle articoli al supporto listino metel
 * Aggiornamento tabelle clieti e fornitori per il supporto escusiole dalle liste
 */

echo "<h4>Inizio aggiornamento archivi versione 44</h4>\n";

$_versione = "44";
$_punto = "0";

$query = "ALTER TABLE articoli ADD qta_cartone INT( 5 ) NULL AFTER scac , ADD qta_multi_ord INT( 5 ) NULL AFTER qta_cartone";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella articoli agg. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agg. $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "43.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}

$query = "ALTER TABLE articoli DROP listino1 , DROP listino2 , DROP listino3 ";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella articoli agg. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agg. $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "43.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}

$query = "ALTER TABLE articoli ADD lead_time CHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER pesoart ,
ADD prod_composto CHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER lead_time ,
ADD stato_prod CHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER prod_composto ,
ADD data_var DATE NOT NULL DEFAULT '0000-00-00' AFTER stato_prod";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella articoli agg. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agg. $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "43.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}




$query = "ALTER TABLE articoli ADD qta_cartone_2 INT( 5 ) NULL AFTER artfor2 ,
ADD qta_multi_ord_2 INT( 5 ) NULL AFTER qta_cartone_2 ,
ADD qtaminord_2 INT( 5 ) NULL AFTER qta_multi_ord_2 ,
ADD lead_time_2 CHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER qtaminord_2 ,
ADD prod_composto_2 CHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER lead_time_2 ,
ADD stato_prod_2 CHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER prod_composto_2 ,
ADD data_var_2 DATE NOT NULL DEFAULT '0000-00-00' AFTER stato_prod_2";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella articoli agg. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agg. $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "43.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}

$query = "ALTER TABLE articoli ADD qta_cartone_3 INT( 5 ) NULL AFTER preacqnetto_3 ,
ADD qta_multi_ord_3 INT( 5 ) NULL AFTER qta_cartone_3 ,
ADD qtaminord_3 INT( 5 ) NULL AFTER qta_multi_ord_3 ,
ADD lead_time_3 CHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER qtaminord_3 ,
ADD prod_composto_3 CHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER lead_time_3 ,
ADD stato_prod_3 CHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER prod_composto_3 ,
ADD data_var_3 DATE NOT NULL DEFAULT '0000-00-00' AFTER stato_prod_3";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella articoli agg. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agg. $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "43.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}


$query = "ALTER TABLE articoli ADD es_selezione CHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'NO' AFTER immagine2";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella articoli agg. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agg. $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "43.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}


$query = "ALTER TABLE banche ADD es_selezione CHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'NO' AFTER note , ADD ts TIMESTAMP NOT NULL AFTER es_selezione";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella banche agg. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agg. $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "43.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}

$query = "ALTER TABLE clienti ADD es_selezione CHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'NO' AFTER cod_ute_dest";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella clienti agg. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agg. $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "43.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}


$query = "ALTER TABLE fornitori ADD es_selezione CHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'NO' AFTER cod_ute_dest";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella fornitori agg. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agg. $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "43.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}



$query = "ALTER TABLE articoli ADD artcorr_2 VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER artcorr , ADD artcorr_3 VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER artcorr_2";

$conn->exec($query);
$_punto++;
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella articoli agg. $_versione punto $_punto ..  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agg. $_versione punto $_punto $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "43.sql.db.php";
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

    echo "Aggiornamento tabella versione..... $_versione del 30/01/2015 COMPLETATO !<br>";

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