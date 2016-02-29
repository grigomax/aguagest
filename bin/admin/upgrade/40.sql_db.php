<?php

session_start;
require "../../../setting/vars.php";
$_percorso = "../../";
require_once $_percorso . "librerie/lib_html.php";

//connettiamoci al database
$conn = connessione_mysql("PDO", $query, $_parametri);


/* AGGIORNAMENTO TABELLE DATABASE AL 15/10/2014
  Aggiornamento tabella articoli per l'inserimento nuova immagine tecnica
 * aggiornamento seconda riga destinazione ragione sociale

 */

echo "<h4>Inizio aggiornamento archivi versione 40</h4>\n";


$query = "ALTER TABLE articoli ADD immagine2 VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER egpz";

$conn->exec($query);

if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella articoli agg. 40 punto 1.........  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agg. 40 punto 1 $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "40.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}



$query = "ALTER TABLE bvfor_testacalce ADD dragsoc2 VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER dragsoc";

$conn->exec($query);

if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella bvfor_testacalce agg. 40 punto 2.........  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agg. 40 punto 2 $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "40.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}


$query = "ALTER TABLE bv_bolle ADD dragsoc2 VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER dragsoc";

$conn->exec($query);

if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella bv_bolle agg. 40 punto 3.........  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agg. 40 punto 3 $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "40.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}


$query = "ALTER TABLE co_testacalce ADD dragsoc2 VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER dragsoc";

$conn->exec($query);

if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella co_testacalce agg. 40 punto 4.........  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agg. 40 punto 4 $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "40.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}

$query = "ALTER TABLE pv_testacalce ADD dragsoc2 VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER dragsoc";

$conn->exec($query);

if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella pv_testacalce agg. 40 punto 5.........  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agg. 40 punto 5 $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "40.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}


$query = "ALTER TABLE of_testacalce ADD dragsoc2 VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER dragsoc";

$conn->exec($query);

if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella of_testacalce agg. 40 punto 6.........  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agg. 40 punto 6 $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "40.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}


$query = "ALTER TABLE fv_testacalce ADD dragsoc2 VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER dragsoc";

$conn->exec($query);

if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella fv_testacalce agg. 40 punto 7.........  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agg. 40 punto 7 $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "40.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}



$query = "ALTER TABLE oc_testacalce ADD dragsoc2 VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER dragsoc";

$conn->exec($query);

if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella oc_testacalce agg. 40 punto 8.........  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agg. 40 punto 8 $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "40.sql.db.php";
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
    echo "Errore File update archivi n. 39 <br>\n";
    exit;
}
else
{

    echo "Aggiornamento tabella versione..... 40 del 20/10/2014 COMPLETATO !<br>";

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