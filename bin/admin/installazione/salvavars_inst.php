<?php

/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../../";
require $_percorso . "../setting/vars.php";
session_start();
$_SESSION['keepalive'] ++;


echo "<!DOCTYPE html>\n";
echo "<html lang=\"it\">\n";
echo "<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
#echo "<meta http-equiv=\"Expires\" content=\"-1\" />\n";
#echo "<meta http-equiv=\"Pragma\" content=\"no-cache\" />\n";
echo "<LINK REL=\"shortcut icon\" HREF=\"" . $_percorso . "images/favicon.ico\">\n";
echo "<title>$title</title>\n";
echo "<link rel=\"stylesheet\" href=\"" . $_percorso . "css/globale.css\" type=\"text/css\">\n";
        echo "<BODY background=\"" . $_percorso . "images/aguaback.jpg\">\n";

// ora devo prendermi i post..
// poi devo creare il files contenete la esportazione
// e lo creo in questa directory plugins

if (($_POST['host'] == "") or ( $_POST['sito'] == "") or ( $_POST['db_server'] == "") or ( $_POST['db_user'] == "") or ( $_POST['azienda'] == ""))
{
    echo "<center>";
    echo "<h2>Controllo dati inseriti</h2>";
    echo "<h3>Uno di questi campi obbligatori risulta vuoto</h3>";
    echo "Nome host server locale = " . $_POST['host'] . "<br>";
    echo "Percorso assoluto = " . $_POST['sito'] . "<br>";
    echo "Nome host server Mysql = " . $_POST['db_server'] . "<br>";
    echo "Nome utente mysql = " . $_POST['db_user'] . "<br>";
    echo "Password Mysql = " . $_POST['db_password'] . "<br>";
    echo "Ragione sociale = " . $_POST['azienda'] . "<br>";

    echo "La preghiamo di tornare indietro e verificare i campi con l'asterisco<br>";
    echo "<br><A HREF=\"#\" onClick=\"history.back()\">Riprova</A>";

    exit;
}



//inizio parte produttiva
// il nome del files
// nome con full percorso
//$nfile="/sito/fatture/vars2.php";
$nfile = "../../../setting/vars.php";

// creo il files e nascondo la soluzione
$fp = fopen($nfile, "w");
//controllo l'esito
if (!$fp)
    die("Errore.. non sono riuscito a creare il file.. Permessi ?");

#scriviamo una riga di commento per chiarire le posizioni
// scriviamo le righe e le verifico
//scriviamo che un file php
$_commento = "<?php\n";
fwrite($fp, $_commento);
if (!$fp)
    die("Errore.. Riga non inserita ?");


$_commento = "// file di configurazione del programma\n";
fwrite($fp, $_commento);
if (!$fp)
    die("Errore.. Riga non inserita ?");

$_commento = 'date_default_timezone_set(\'Europe/Rome\');' . "\n";

fwrite($fp, $_commento);
if (!$fp)
    die("Errore.. Riga non inserita ?");

$_commento = 'include "vars_aspetto.php";' . "\n";
fwrite($fp, $_commento);
if (!$fp)
    die("Errore.. Riga non inserita ?");

$_scrivi = sprintf('$host = "%s";' . "\n", $_POST['host']);
fwrite($fp, $_scrivi);
if (!$fp)
    die("Errore.. Riga non inserita ?");

$_scrivi = sprintf('$sito = "%s";' . "\n", $_POST['sito']);
fwrite($fp, $_scrivi);
if (!$fp)
    die("Errore.. Riga non inserita ?");

$_scrivi = sprintf('$title = "Agua Gest | %s";' . "\n", $_POST['azienda']);
fwrite($fp, $_scrivi);
if (!$fp)
    die("Errore.. Riga non inserita ?");

$_scrivi = sprintf('$db_server = "%s";' . "\n", $_POST['db_server']);
fwrite($fp, $_scrivi);
if (!$fp)
    die("Errore.. Riga non inserita ?");

$_scrivi = sprintf('$db_user = "%s";' . "\n", $_POST['db_user']);
fwrite($fp, $_scrivi);
if (!$fp)
    die("Errore.. Riga non inserita ?");

$_scrivi = sprintf('$db_password = "%s";' . "\n", $_POST['db_password']);
fwrite($fp, $_scrivi);
if (!$fp)
    die("Errore.. Riga non inserita ?");

$_scrivi = sprintf('$db_nomedb = "%s";' . "\n", $_POST['db_nomedb']);
fwrite($fp, $_scrivi);
if (!$fp)
    die("Errore.. Riga non inserita ?");

$_scrivi = sprintf('$azienda = "%s";' . "\n", $_POST['azienda']);
fwrite($fp, $_scrivi);
if (!$fp)
    die("Errore.. Riga non inserita ?");

// chiudiamo il file php
$_commento = "?>";
fwrite($fp, $_commento);
if (!$fp)
    die("Errore.. Riga non inserita ?");


// chiudiamo il files
fclose($fp);


include "../../../setting/vars.php";
echo "<center>";
echo "<h2>Installazione Agua Gest Passo 3 di 6</h2>\n";
echo "<h2>Creazione parametri riuscita <br> ora prova database</h2>";

try
{
    $conn = new PDO("mysql:host=$db_server", $db_user, $db_password);
}
catch (PDOException $e)
{
    echo 'Errore di connessione: ' . $e->getMessage();
    echo "<h2>Non trovo il database server oppure dati immessi errati</h2>";
    exit;
}


$conn->exec("CREATE SCHEMA IF NOT EXISTS $db_nomedb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
//echo $conn->errorCode();
//$errorinfo = $conn->errorInfo();
//if($errorinfo['1'] == "")
if ($conn->errorCode() == "00000") // ... tutto ok
{
    echo "Creazione database $db_nomedb Riuscita perfettamente<br><br>";
    if ($_POST['azione'] == "recovery")
    {
        echo "Ora bisogna Importare le copie del database..<BR><BR>";
        echo "<a href=\"../../../upload/upload.php?cosa=database\">Premi qui per continuare con l'importazione..</a>";
    }
    else
    {
        //impostiamo la sessione..
        $_SESSION['user']['user'] = "admin";
        echo "Ora bisogna creare tutta la struttura del database<BR><BR>";
        echo "<a href=\"../installazione/importadati.php\">Premi qui per continuare l'installazione del programma</a>";
    }
    echo "</body></html>";
}
else
{
    echo "Errore nella creazione del database $db_nomedb<br><br>";
    $errorinfo = $conn->errorInfo();
    echo $errorinfo[2] . "<br/>"; // stringa con l' errore
    $_errori['descrizione'] = $errorinfo[2];
    $_errori['files'] = "salvavars.php";
    scrittura_errori($_cosa, $_percorso, $_errori);

    echo "<a href=\"parametri.php\">Prova a vedere i paramentri immessi cliccando qui</a>";
    echo "</body></html>";
}



echo "</body></html>";
?>