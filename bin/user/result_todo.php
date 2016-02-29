<?php

/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../";
require $_percorso . "../setting/vars.php";
session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

require "../librerie/motore_anagrafiche.php";
//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

testata_html($_cosa, $_percorso);
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['user'] != "")
{

    echo "<table align=\"left\" width=\"100%\">\n";
    echo "<tr><td align=\"center\">\n";

    echo "<h3 align=\"center\">Risultato Cose da fare</h3>";
    
    //iniziamo l'inserimento dei dati nell'archivio..
    //tutti i parametri vanno inseriti nell'array parametri e passati alla funzione
    $_parametri['utente_end'] = $_POST['utente_end'];
    $_parametri['data_start'] = cambio_data("us", $_POST['data_start']);
    $_parametri['data_end'] = cambio_data("us", $_POST['data_end']);
    $_parametri['titolo'] = addslashes($_POST['titolo']);
    $_parametri['completato'] = $_POST['completato'];
    $_parametri['priorita'] = $_POST['priorita'];
    $_parametri['corpo'] = addslashes($_POST['corpo']);
        
    $_parametri['utente_start'] = $_SESSION['user']['id'];

    $_anno = $_POST['anno'];
    $_numero = $_POST['numero'];
    
    if ($_POST['azione'] == "Inserisci")
    {
        $_parametri['anno'] = date('Y');
        $_risultato = tabella_todo("inserisci", $_anno, $_numero, $_utente_end, $_data_end, $_completato, $_parametri);
        $_scritta = "<h5>Inserimento dati</h5>\n";
    }
    elseif ($_POST['azione'] == "Aggiorna")
    {
        $_risultato = tabella_todo("aggiorna", $_anno, $_numero, $_utente_end, $_data_end, $_completato, $_parametri);
        $_scritta = "<h5>Aggiornamento dati</h5>\n";
        
        
    }
    elseif ($_POST['azione'] == "Elimina")
    {

        $_risultato = tabella_todo("elimina", $_anno, $_numero, $_utente_end, $_data_end, $_completato, $_parametri);
        $_scritta = "<h5>Eliminazioni Scadenza</h5>\n";
    }
    else
    {
        $_scritta = "<h5>Annullamento operazione come richiesto</h5>\n";
    }

//leggiamo gli errori..
// Esegue la query...
    if ($_risultato != "OK")
    {
        echo $_risultato['errori']['descrizione'];
    }
    else
    {
        echo $_scritta;
        echo "<h5>Ok. Operazione andata a buon Fine</h5>\n";
    }


    echo "</td></tr></table></html>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>