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
ini_set('session.gc_maxlifetime', $SESSIONTIME);
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



if ($_SESSION['user']['magazzino'] > "1")
{
//inserimento.. documenti ordine fornitori

    $_azione = $_POST['scrivi'];

    if ($_azione == "Inserisci")
    {
# 18 post
        include ( "ddtacq.inc" );
        if (insert_doc($_POST['ndoc'], $_POST['annodoc'], $_POST['daydoc'], $_POST['mesedoc'], $_POST['ddtacq'], $_POST['fatturacq'], $_POST['protoiva']))
        {
            // Se ci sono errori la funzione pensa a stamparmi il codice d'errore...
            exit(0);
        }
        $id = session_id();


        // Stringa contenente la query di ricerca...
        $query = sprintf("delete from of_basket where sessionid=\"%s\" ", $id);

        // Esegue la query...
        if (mysql_query($query, $conn) != 1)
        {
            echo "Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
            return -1;
        }

        // elimino le sessioni usate
        unset($_SESSION['fornitore']);
        unset($_SESSION['importi']);
        unset($_SESSION['ndoc']);
        unset($_SESSION['anno']);
        unset($_SESSION['calce']);


        echo "<center><b>Documento inserito perfettamente</a></center>";


        return;
    }

// aggiorno il documento
    if ($_azione == "Aggiorna")
    {
        include ( "ddtacq.inc" );
# 18 post
        if (update_doc($_POST['ndoc'], $_POST['annodoc'], $_POST['datareg'], $_POST['ddtacq'], $_POST['fatturacq'], $_POST['protoiva']))
        {

            // Se ci sono errori la funzione pensa a stamparmi il codice d'errore...
            exit(0);
        }

        $id = session_id();

        // Stringa contenente la query di ricerca... solo dei fornitori
        $query = sprintf("delete from of_basket where sessionid=\"%s\" ", $id);

        // Esegue la query...
        if (mysql_query($query, $conn) != 1)
        {
            echo "Si &egrave; verificato un errore nella query:<br>\n\"$query\"\n";
            return -1;
        }

        // elimino le sessioni usate
        unset($_SESSION['fornitore']);
        unset($_SESSION['importi']);
        unset($_SESSION['ndoc']);
        unset($_SESSION['anno']);
        unset($_SESSION['calce']);


        echo "<center><b>Documento Aggiornato perfettamente</a></center>";

        return;
    }
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>