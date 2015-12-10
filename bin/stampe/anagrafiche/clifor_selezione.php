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
ini_set('session.gc_maxlifetime', $SESSIONTIME);
session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

//verifichiamo innanzitutto se parliamo di clienti o fornitori..
#recuperiamo le vriabili...

$_codini = $_POST['codini'];
$_codfine = $_POST['codfine'];
$_stampa = $_POST['stampa'];
$_campi = $_POST['campi'];
$_descrizione = $_POST['descrizione'];

$_utente = $_GET['fornitore'];

if ($_utente == "fornitori")
{
    $_tabella = "clienti";
}
else
{
    $_tabella = "fornitori";
}

$_parametri[tabella] = "Stampa Anagrafica $_tabella";


#eseguiamo la query
if ($_codini != "")
{
    $query = "SELECT * FROM $_tabella WHERE ragsoc >= '$_codini' AND ragsoc <= '$_codfine' ORDER BY ragsoc";
}
else
{
    $query = "SELECT * FROM $_tabella WHERE $_campi LIKE '%$_descrizione%' ORDER BY ragsoc";
}

//echo $query; echo $_stampa;

if ($_stampa == "clifor_pdf")
{
    #include "clifor_pdf.php";
    //viao
}
else
{

    include ("clifor.php");
}
?>