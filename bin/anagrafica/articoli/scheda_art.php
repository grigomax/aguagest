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
require_once $_percorso . "librerie/motore_anagrafiche.php";
require_once $_percorso . "librerie/stampe_pdf.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


if ($_SESSION['user']['anagrafiche'] > "1")
{

// mi prendo il GET appena passato

    $_articolo = $_GET['articolo'];

    $V001 = "Scheda Articolo";
    $V002 = "Codice";
    $V003 = "Descrizione";
    $V004 = "U.M.";
    $V005 = "Listino";
    $V006 = "Disponibilit&agrave;";
    $V007 = "Acquista on-line uno sconto per te !";
    $V008 = "Aggiungi al Carrello ==> ";
    $V009 = "Dettagli articolo :";
    $V010 = "Torna indietro... ";
    $V011 = "Legenda Disponibilit&agrave; ";
    $V012 = "Attualmente Non Disponibile ";
    $V013 = "Pochi pezzi disponibili ";
    $V014 = "Buona ";
    $V015 = "Molto Buona ";
    $V016 = "La tua posizione ";
    $V017 = "Trovato articolo correlato";
    $V018 = "Visualizza Articolo";

    $dati = tabella_articoli("singola_prezzo", $_GET['articolo'], "1");


    $_azione = "Invia";
    $link = substr("http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'], 0,(strpos("http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'], '?')));

    $_parametri['link'] = $link."?azione=PDF&articolo=$_articolo";
    
    
    
    //qui iniziamo a costruire la pagina direttamente in pdf..
    //creaiamo il file
    crea_file_pdf($_cosa, $_orientamento, "Scheda Articolo " . $dati['articolo']);

    crea_pagina_pdf();

    crea_intestazione_ditta_pdf("conlogo_cat", "Scheda Articolo " . $dati['articolo'], $_anno, $_pg, $pagina, $_parametri);

    //qui mi conviene comporla a mano..
    intesta_pagina("titolo", "Scheda Articolo", $_parametri);

    intesta_pagina("sotto_titolo", "Scheda Articolo", $_parametri);

    corpo_pagina("scheda_articolo", $dati, $_parametri);

    if ($dati['artcorr'] != "")
    {

        $dati_corr = tabella_articoli("singola_prezzo", $dati['artcorr'], "1");
        corpo_pagina("articolo_correlato", $dati_corr, $_parametri);
    }


    if ($dati['artcorr_2'] != "")
    {

        $dati_corr = tabella_articoli("singola_prezzo", $dati['artcorr_2'], "1");

        corpo_pagina("articolo_correlato", $dati_corr, $_parametri);
    }

    corpo_pagina("calce_scheda", $dati, $_parametri);


    if ($_GET['azione'] == "PDF")
    {
        $_pdf = chiudi_files("Articolo_" . $dati[articolo], "../../..", "F");


        //prepariamo la maschera per scrivere
        maschera_invio_posta("singolo", $_percorso, $_pdf, $email2, $dati2['email2'], "Scheda", $_parametri);
    }
    else
    {
        $_pdf = chiudi_files("Articolo_" . $dati[articolo], "../../..", "I");
    }
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>