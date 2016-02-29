<?php

session_start;
require "../../../setting/vars.php";
$_percorso = "../../";
require_once $_percorso . "librerie/lib_html.php";

//connettiamoci al database
$conn = connessione_mysql("PDO", $query, $_parametri);


/* AGGIORNAMENTO TABELLE DATABASE AL 27/11/2014
  Aggiornamento tabella utenti ed inserimento nuovo arcivio banned_ip
 */

echo "<h4>Inizio aggiornamento archivi versione 41</h4>\n";


$query = "CREATE TABLE IF NOT EXISTS banned_ip (ip_remoto varchar(20) NOT NULL,
    n_volte int(1) NOT NULL DEFAULT '0',  user_agent varchar(150) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8";

$conn->exec($query);

if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione creazione tabella banned_ip agg. 41 punto 1.........  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agg. 41 punto 1 $db_nomedb<br>";

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


$query = "ALTER TABLE utenti ADD blocco CHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'NO' AFTER plugins";


$conn->exec($query);

if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Alterazione creazione tabella utenti agg. 41 punto 2.........  riuscita perfettamente <br>";
    $_num++;
    $fine = 1;
}
else
{
    echo "Alterazione tabella agg. 41 punto 2 $db_nomedb<br>";

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


$query = "ALTER TABLE utenti ADD nvolte INT( 1 ) NOT NULL DEFAULT '0' AFTER blocco";

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


//------------------------------------------------------------------------------
//    Verifico che non ci siano stati errori
if ($fine != "1")
{
#blocco il programma ancor prima di iniziare..
    echo "Impossibile continuare con il programma causa errori <br>";
    echo "Si richiede assistenza Ricopiare o stampare questa pagina <br>\n";
    echo "Errore File update archivi n. 41 <br>\n";
    exit;
}
else
{

    echo "Aggiornamento tabella versione..... 41 del 01/12/2014 COMPLETATO !<br>";

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