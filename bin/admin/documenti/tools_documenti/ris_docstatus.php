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

//settiamo il tempo di sessione
session_start();
$_SESSION['keepalive'] ++;

//carichiamo le librerie base con al gestione degli errori
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['setting'] > "2")
{

    //alleghiamo la libreria corretta
    require $_percorso . "librerie/motore_doc_pdo.php";

    // prendo le variabili

    $_anno = $_POST['anno'];
    $_ndoc = $_POST['ndoc'];
    $_status = $_POST['status'];
    $_tdoc = $_GET['tdoc'];
    $_suffix = strtoupper($_POST['suffix']);

    //selezioniamo il database con il documento corretto
    //selezioniamo il database documenti..
    $_dbdoc = archivio_tdoc($_tdoc);

     echo "<table width=\"100%\">\n";
    echo "<tr>\n";
    echo "<td align=\"center\" valign=\"top\" colspan=\"2\"><span class=\"intestazione\"><b>Risulatato modifica documento $_tdoc</b><br></span><br>\n";

    // Stringa contenente la query di ricerca..
    // aggiorno l'intestazione nelle testa calce

    $query = sprintf("update $_dbdoc[testacalce] set status=\"%s\" where anno=\"%s\" and suffix='$_suffix' and ndoc=\"%s\"", $_status, $_anno, $_ndoc);

    $result = domanda_db("exec", $query, $_cosa, $_ritorno, "verbose");


    echo "<b> Documento aggiornato perfettamente </b></td></tr>\n";

    echo "</body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>