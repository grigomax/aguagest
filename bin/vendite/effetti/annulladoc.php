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
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica", $_percorso);



//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "1")
{

    $_azione = $_POST['azione'];

    if ($_azione == "")
    {
        $_azione = "Annulla";
    }

    if ($_azione == "Annulla")
    {
        printf("<form action=\"annulladoc.php\" method=\"POST\">");
        printf("<p align=\"center\" class=\"testo_blu\">Sei sicuro di Abbandonare il documento ?<br><input type=\"submit\" name=\"azione\" value=\"Abbandona\"> - <A HREF=\"#\" onClick=\"history.back()\">Torna</A> </form>");

        exit;
    }

    if ($_azione == "Abbandona")
    {


//recupero le sessioni

        $_ndoc = $_SESSION['ndoc'];
        $_anno = $_SESSION['anno'];
        $_tdoc = $_SESSION['tdoc'];

        $id = session_id();

        // elimino le sessioni usate
        // molto importante non eliminare le sessioni di lavoro
        unset($_SESSION['cliente']);
        unset($_SESSION['anno']);
        unset($_SESSION['ndoc']);
        unset($_SESSION['tdoc']);
        unset($_SESSION['importi']);
        unset($_SESSION['totiva']);
        unset($_SESSION['calce']);


        echo "<center><h2>Operazione Annullata con successo</h2></center>";
    }
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>