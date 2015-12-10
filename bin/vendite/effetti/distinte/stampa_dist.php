<?php

/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../../../";
require $_percorso . "../setting/vars.php";
session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

require $_percorso . "librerie/motore_anagrafiche.php";

require "distinta_eff.php";

$_parametri['PRINT_FONT_SIZE'] = 10;

base_html_stampa("chiudi", $_parametri);

if ($_SESSION['user']['vendite'] > "2")
{
//include anno le prime 12 cifre

    $_ndistinta = substr($_GET['ndistinta'], 10, 4);
    $_datadist = substr($_GET['ndistinta'], 0, 10);
    $_azione = $_GET['azione'];



// Stringa contenente la query di ricerca... solo dei fornitori
    $query = "SELECT *, date_format(datadist,'%d-%m-%Y') AS datadist, scadeff AS ordine, date_format(scadeff,'%d-%m-%Y') AS scadeff, date_format(datadoc,'%d-%m-%Y') AS datadoc FROM effetti INNER JOIN clienti ON effetti.codcli = clienti.codice where ndistinta=\"$_ndistinta\" and datadist=\"$_datadist\" ORDER BY ordine";

    $result = $conn->query($query);
    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }

    foreach ($result AS $datie)
        ;

    $_status = $datie['status'];
    $_dataus = $datie['datareg'];

    $datib = tabella_banche("singola", $datie['bancadist'], $_abi, $_cab, $_parametri);

    $result = $conn->query($query);
    if ($conn->errorCode() != "00000")
    {
        $_errore = $conn->errorInfo();
        echo $_errore['2'];
        //aggiungiamo la gestione scitta dell'errore..
        $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
        $_errori['files'] = "$_SERVER[SCRIPT_FILENAME]";
        scrittura_errori($_cosa, $_percorso, $_errori);
    }
//cerco il numero di righe
    $righe = $result->rowCount();
//echo $righe;
//inserisco il numero di righe per pagina
    $rpp = 6;

#dobbiamo aggiungere al numero di effetti da stampare anche il sommario alla fine
    $righe = $righe + 1;


    $_pagine = $righe / $rpp;
//arrotondo per eccesso
    $pagina = ceil($_pagine);

    for ($_pg = 1; $_pg <= $pagina; $_pg++)
    {

        testata_dis($datib, $datie, $_pg, $pagina, $_azione, $_emaildestino);

        $_corpo = corpo_dis($_pg, $pagina, $rpp, $result, $datie, $_nr, $_importi, $_corpo);

        $_importi = $_corpo['somma'];

        calce_dis($pagina, $_pg, $righe, $_importi);
    }
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>