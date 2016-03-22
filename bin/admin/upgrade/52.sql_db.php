<?php

session_start;
require "../../../setting/vars.php";
$_percorso = "../../";
require_once $_percorso . "librerie/lib_html.php";

//connettiamoci al database
$conn = connessione_mysql("PDO", $query, $_parametri);


/* AGGIORNAMENTO TABELLE DATABASE AL 20/03/2016
 * 
 * Aggiornamento Dimensioni del campo CAP (codice avvviamento Postale.. 
 * portandolo da 5 a 8
 * 
 * Aggiunta al magazzino del suffisso ed anno protocollo per il collegamento con 
 * la contabilità
 * 
 * Aggiunto un nuovo campo agli articoli l'immagine 3
 * 
 * Aggoiunto ai clienti e fornitori la e-mail pec.
 * 
 */

echo "<h4>Inizio aggiornamento archivi versione 52</h4>\n";

$_versione = "52";
$_punto = "0";

$_sovrascrivi = "NO";

//cambiamo tutti i valori dei documenti..

$query = "ALTER TABLE articoli ADD immagine3 VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER es_selezione";

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
    scrittura_errori($_tipo, $_cosa, $_errori, $query);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    if($_sovrascrivi == "SI")
    {
        $fine = 1;
        $_num++;
    }
    else
    {
        $fine = 0;
        exit;
    }
}


$query = "ALTER TABLE bv_bolle CHANGE dcap dcap VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";

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
    scrittura_errori($_tipo, $_cosa, $_errori, $query);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    if($_sovrascrivi == "SI")
    {
        $fine = 1;
        $_num++;
    }
    else
    {
        $fine = 0;
        exit;
    }
}

$query = "ALTER TABLE co_testacalce CHANGE dcap dcap VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";

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
    scrittura_errori($_tipo, $_cosa, $_errori, $query);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    if($_sovrascrivi == "SI")
    {
        $fine = 1;
        $_num++;
    }
    else
    {
        $fine = 0;
        exit;
    }
}

$query = "ALTER TABLE pv_testacalce CHANGE dcap dcap VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";

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
    scrittura_errori($_tipo, $_cosa, $_errori, $query);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    if($_sovrascrivi == "SI")
    {
        $fine = 1;
        $_num++;
    }
    else
    {
        $fine = 0;
        exit;
    }
}

$query = "ALTER TABLE of_testacalce CHANGE dcap dcap VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";

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
    scrittura_errori($_tipo, $_cosa, $_errori, $query);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    if($_sovrascrivi == "SI")
    {
        $fine = 1;
        $_num++;
    }
    else
    {
        $fine = 0;
        exit;
    }
}

$query = "ALTER TABLE fv_testacalce CHANGE dcap dcap VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";

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
    scrittura_errori($_tipo, $_cosa, $_errori, $query);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    if($_sovrascrivi == "SI")
    {
        $fine = 1;
        $_num++;
    }
    else
    {
        $fine = 0;
        exit;
    }
}

$query = "ALTER TABLE oc_testacalce CHANGE dcap dcap VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";

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
    scrittura_errori($_tipo, $_cosa, $_errori, $query);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    if($_sovrascrivi == "SI")
    {
        $fine = 1;
        $_num++;
    }
    else
    {
        $fine = 0;
        exit;
    }
}

$query = "ALTER TABLE destinazioni CHANGE dcap dcap VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";

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
    scrittura_errori($_tipo, $_cosa, $_errori, $query);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    if($_sovrascrivi == "SI")
    {
        $fine = 1;
        $_num++;
    }
    else
    {
        $fine = 0;
        exit;
    }
}


$query = "ALTER TABLE clienti CHANGE cap cap VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE dcap dcap VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";

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
    scrittura_errori($_tipo, $_cosa, $_errori, $query);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    if($_sovrascrivi == "SI")
    {
        $fine = 1;
        $_num++;
    }
    else
    {
        $fine = 0;
        exit;
    }
}


$query = "ALTER TABLE fornitori CHANGE cap cap VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE dcap dcap VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";

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
    scrittura_errori($_tipo, $_cosa, $_errori, $query);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    if($_sovrascrivi == "SI")
    {
        $fine = 1;
        $_num++;
    }
    else
    {
        $fine = 0;
        exit;
    }
}


$query = "ALTER TABLE clienti ADD email_certi VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER es_pubblicita";

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
    scrittura_errori($_tipo, $_cosa, $_errori, $query);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    if($_sovrascrivi == "SI")
    {
        $fine = 1;
        $_num++;
    }
    else
    {
        $fine = 0;
        exit;
    }
}

$query = "ALTER TABLE fornitori ADD email_certi VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER es_selezione";

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
    scrittura_errori($_tipo, $_cosa, $_errori, $query);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    if($_sovrascrivi == "SI")
    {
        $fine = 1;
        $_num++;
    }
    else
    {
        $fine = 0;
        exit;
    }
}


//magazzino..

$query = "ALTER TABLE magazzino ADD suffix_proto CHAR(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'A' AFTER protoiva, ADD anno_proto YEAR(4) NOT NULL AFTER suffix_proto";

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
    scrittura_errori($_tipo, $_cosa, $_errori, $query);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    if($_sovrascrivi == "SI")
    {
        $fine = 1;
        $_num++;
    }
    else
    {
        $fine = 0;
        exit;
    }
}



$query = "ALTER TABLE magastorico ADD suffix_proto CHAR(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'A' AFTER protoiva, ADD anno_proto YEAR(4) NOT NULL AFTER suffix_proto";


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
    scrittura_errori($_tipo, $_cosa, $_errori, $query);
    echo "<br>Si prega di contattale l'amministratore con comunicare l'errore qui sopra con un copia incolla<br>\n";
    echo "Oppure comunicare il file agua_log presente nella directory spool<br>Impossibile continuare Errore Registrato";
    echo "</body></html>";
    if($_sovrascrivi == "SI")
    {
        $fine = 1;
        $_num++;
    }
    else
    {
        $fine = 0;
        exit;
    }
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

    echo "Aggiornamento tabella versione..... $_versione del 20/03/2016 COMPLETATO !<br>";

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
        scrittura_errori($_tipo, $_cosa, $_errori, $query);

        $fine = 0;
    }
}
?>