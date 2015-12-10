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
require "../../librerie/motore_anagrafiche.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "1")
{

    echo "<table width=\"100%\">\n";
    echo "<tr><td align=\"center\" width=\"80%\" valign=\"top\">\n";

    echo "<table width=\"100%\" align=\"center\">\n";
// inserisci
    $_azione = $_POST['azione'];

    if ($_azione == "Inserisci")
    {
// verifica inserimento cliente
        //Controlliamo se il codice è ancora libero

        $dati = tabella_agenti("singola", $_POST['codice'], $_parametri);

        if ($dati)
        {
            echo "<tr><td><b>Agente inserito &egrave; gi&agrave; esistente nell'archivio.</td></tr>\n";

            echo "<tr><td>Controllare i seguenti campi nell'archivio per verificare la presenza</td></tr>\n";

            printf("<tr><td>Codice agente immesso = \"%s\", codice agente trovato nell'archivio \"%s\"</td></tr>\n", $_POST['codice'], $dati['codice']);

            printf("<tr><td>Numero Partita Iva = \"%s\", Partita iva trovata nell'archivio \"%s\"</td></tr>\n", $_POST['piva'], $dati['piva']);

            echo "<tr><td>Fai indietro con il browser per non perdere i dati inseriti.<br> Poi cambia codice cliente</td></tr>\n";

            exit;
        }
        else
        {
            $result = tabella_agenti("Inserisci", $_POST['codice'], $_POST);

            if ($result != "OK")
            {
                echo "<tr><td> Errore Inserimento agenti</td></tr>\n";
            }
            else
            {
                echo "<tr><td> Agente inserito correttamente</td></tr>\n";
            }
        }// fine graffa else
    }// fine graffa funzione




    if ($_azione == "Aggiorna")
    {
        $result = tabella_agenti("Aggiorna", $_POST['codice'], $_POST);

        if ($result != "OK")
        {
            echo "<tr><td> Errore Modifica agenti</td></tr>\n";
        }
        else
        {
            echo "<tr><td> Agente Modificato correttamente</td></tr>\n";
        }
    }



    if ($_azione == "Elimina")
    {

        $_utente = $_POST['codage'];


        $query = sprintf("select anno, ndoc, utente, agente from fv_testacalce where agente=\"%s\" limit 10 ", $_POST['codice']);

        $result = $conn->query($query);

        if ($conn->errorCode() != "00000")
        {
            $_errore = $conn->errorInfo();
            echo $_errore['2'];
            //aggiungiamo la gestione scitta dell'errore..
            $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
            $_errori['files'] = "risineage.php";
            scrittura_errori($_cosa, $_percorso, $_errori);
        }

        //echo $res;
        // La query e' stata eseguita con successo...
        // Non so' se ci sono articoli con quel codice...
        if ($result->rowCount() > 0)
        {
            echo " Impossibile eliminare Il cliente perchè trovato nei seguenti muovimenti.";
            echo " <table border=1><tr>";
            echo " <td>articolo</td><td>anno</td><td>n. doc.</td><td>utente</td></tr>";

            foreach ($result AS $dati)
            {

                printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $dati['anno'], $dati['ndoc'], $dati['utente'], $dati['agente']);
            }
            echo "</table>";
        }
        else
        {
            $query = sprintf("DELETE FROM agenti WHERE codice=\"%s\" limit 1", $_POST['codice']);

            $result = $conn->exec($query);

            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
                $_errori['files'] = "risineage.php";
                scrittura_errori($_cosa, $_percorso, $_errori);
                echo "<br>Eliminazione agente NON riuscita";
            }
            else
            {
                echo "<br>Eliminazione agente Riuscita";
            }
        }
    }

    echo "</td></tr></table>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>