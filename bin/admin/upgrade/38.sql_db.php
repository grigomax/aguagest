<?php

session_start;
require "../../../setting/vars.php";
$_percorso = "../../";
require_once $_percorso . "librerie/lib_html.php";

//connettiamoci al database
$conn = connessione_mysql("PDO");


/* AGGIORNAMENTO TABELLE DATABASE AL 10/07/2014
  Aggiornamento tabelle agenti

 */

echo "<h4>Inizio aggiornamento archivi versione 38</h4>\n";


//eliminazione archivio aaa

$query = "ALTER TABLE provvigioni ADD liquid CHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'NO' AFTER provvigioni ,
ADD status VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER liquid";

$conn->exec($query);

if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella agenti agg. 38 punto 1.........  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agenti agg. 38 punto 1 $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "38.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}


$query = "ALTER TABLE scadenziario ADD status VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL";

$conn->exec($query);

if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella scadenziario agg. 38 punto 2.........  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agenti agg. 38 punto 2 $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "38.sql.db.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    $fine = 0;
    exit;
}




$query = "ALTER TABLE `scadenziario` ADD `data_pag` DATE NOT NULL DEFAULT '0000-00-00'";

$conn->exec($query);

if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione tabella scadenziario agg. 38 punto 3.........  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agenti agg. 38 punto 3 $db_nomedb<br>";

    $errorinfo = $conn->errorInfo();
    echo "Errore numero $errorinfo[1] tipo $errorinfo[2] <br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "38.sql.db.php";
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
    echo "Errore File update archivi n. 38 <br>\n";
    exit;
}
else
{

    echo "Aggiornamento tabella versione..... 38 del 10/07/2014 COMPLETATO !<br>";

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