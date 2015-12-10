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
$conn = permessi_sessione("verifica", $_percorso);

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['magazzino'] > "2")
{

    require_once "travaso_brcl.inc";



// recupero la variabile azione

    $_azione = $_POST['azione'];

// se annullata rimando all'indice
    if ($_azione == "Annulla")
    {
        $_azione = "annulla";
        include ("annulladoc.php");
        exit;
    }


    if ($_azione == "Parziale")
    {
// passo i post a travaso inc

        if (insert_tot_doc_ddtreso($_POST['annodaimp'], $_POST['codini'], $_POST['codfine'], $_POST['ndoc'], $_POST['annondoc'], $_POST['daydoc'], $_POST['mesedoc'], $_POST['annodoc'], $_POST['ddtacq'], $_POST['fatturacq']))
        {
            // Se ci sono errori la funzione pensa a stamparmi il codice d'errore...
            exit(0);
        }

        //ridirigo la pagina anzché includerla..
        header("location: corpo_brcl.php");
        #include 'corpo_brcl.php';
        exit;
    }


// in caso di evasione parziale delle righe estratte
    if ($_azione == "Estrai")
    {
# 18 post

        $_ndoc = disponibilita_ndoc($_cosa, "ddtacq", $_SESSION['ndoc'], $_SESSION['annondoc']);

        if (insert_parz_doc_ddtreso($_SESSION['annodaimp'], $_SESSION['codini'], $_SESSION['codfine'], $_ndoc, $_SESSION['annondoc'], $_SESSION['daydoc'], $_SESSION['mesedoc'], $_SESSION['annodoc'], $_SESSION['ddtacq'], $_SESSION['fatturacq'], $_POST['chiudi']))
        {
            // Se ci sono errori la funzione pensa a stamparmi il codice d'errore...
            exit(0);
        }


        echo "<center><b>Documento Magazzino $_ndoc Inserito perfettamente</a></center>";

        


// elimino la sessione precedentemente creata
        unset($_SESSION['fornitore']);
        unset($_SESSION['cliente']);
        unset($_SESSION['annodaimp']);
        unset($_SESSION['codini']);
        unset($_SESSION['codfine']);
        unset($_SESSION['annondoc']);
        unset($_SESSION['daydoc']);
        unset($_SESSION['mesedoc']);
        unset($_SESSION['annodoc']);
        unset($_SESSION['ddtacq']);
        unset($_SESSION['fatturacq']);

        return;
    }
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>