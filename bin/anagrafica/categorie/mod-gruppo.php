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
$conn = permessi_sessione("verifica_PDO", $_percorso);

require "../../librerie/motore_anagrafiche.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "2")
{

    //prendiamoci i post..
    $_tipo = $_GET['tipo'];

    if ($_tipo == "tipart")
    {
        $_descrizione = "Tipologia Articoli";
    }
    else
    {
        $_descrizione = "Categoria Merceologica";
    }

    
    //echo $_POST['imballo'];
// Inizio tabella pagina principale ----------------------------------------------------------

    printf("<form action=\"gruppo.php?tipo=$_tipo\" method=\"POST\">\n");
    echo "<br><br><table border=\"0\" align=center>\n";

    echo "<tr><td colspan=2 align=\"center\"><h2 align=\"center\">Modifica $_descrizione</h2></span></td>\n";
//inizio pagina alternativa

    if ($_POST['azione'] == "Inserisci")
    {
// prendo le variabili passate

        $_codice = $_POST['codice'];
        $_id = $_POST['id'];
        $_descrizione = $_POST['descrizione'];
        $_parametri['id'] = $_POST['id'];
        $_parametri['descrizione'] = addslashes($_POST['descrizione']);
        $_parametri['imballo'] = $_POST['imballo'];

        if ($_tipo == "tipart")
        {
            $check = tabella_tipart("check_codice", $_codice, $_parametri);
        }
        else
        {
            $check = tabella_catmer("check_codice", $_codice, $_parametri);
        }


        if ($check == "NO")
        {
            echo "<td><font color=\"red\">$_selezione $_codice $_descrizione Gi&agrave; presente nell'archivio</font></td>";
        }
        else
        {
            //inseriamo la categoria

            if ($_tipo == "tipart")
            {
                $result = tabella_tipart("inserisci", $_codice, $_parametri);
            }
            else
            {
                $result = tabella_catmer("inserisci", $_codice, $_parametri);
            }


            if ($result == "NO")
            {
                echo "<td><font color=\"red\">$_selezione $_codice $_descrizione Errore inserimento</font></td>";
            }
            else
            {
                echo "<td><font color=\"green\">$_selezione $_codice $_descrizione inserita correttamene nell'archivio</font></td>";
            }
        }

        return;
    }// parentesi fine funzione inserimento
//inizio aggiornamento
    if ($_POST['azione'] == "Modifica")
    {
        $_codice = $_POST['codice'];
        $_parametri['id'] = $_POST['id'];
        $_parametri['descrizione'] = addslashes($_POST['descrizione']);
        $_parametri['imballo'] = $_POST['imballo'];


        if ($_tipo == "tipart")
        {
            $_result = tabella_tipart("aggiorna_id", $_codice, $_parametri);
        }
        else
        {
            $_result = tabella_catmer("aggiorna_id", $_codice, $_parametri);
        }


        if ($_result != "OK")
        {
            echo "errore";
        }
        else
        {
            //aggiorniamo gli articoli..
            if ($_tipo == "tipart")
            {
                $query = "UPDATE articoli SET tipart='$_codice' where tipart='$_codice'";
            }
            else
            {
                $query = "UPDATE articoli SET catmer='$_codice' where catmer='$_codice'";
            }


            $result = $conn->exec($query);
            if ($conn->errorCode() != "00000")
            {
                $_errore = $conn->errorInfo();
                echo $_errore['2'];
                //aggiungiamo la gestione scitta dell'errore..
                $_errori['descrizione'] = "Errore Query $_cosa = $query - $_errore[2]";
                $_errori['files'] = "motore_anagrafiche.php";
                scrittura_errori($_cosa, $_percorso, $_errori);
            }
        }

        echo "<tr><td><font color=\"green\">$_selezione modificato correttamente</font></td></td>";

        return;
    }// parentesi fine funzione inserimento
//inizio eliminazione
    if ($_POST['azione'] == "Elimina")
    {

        if ($_tipo == "tipart")
        {
            $result = tabella_tipart("elimina_id", $_codice, $_POST['id']);
        }
        else
        {
            $result = tabella_catmer("elimina_id", $_codice, $_POST['id']);
        }

            if ($result == "NO")
            {
                echo "<td><font color=\"red\">$_selezione $_codice $_descrizione Errore eliminazione</font></td>";
            }
            else
            {
                echo "<td><font color=\"green\">$_selezione $_codice $_descrizione eliminazione correttamene nell'archivio</font></td>";
            }

        return;
    }// parentesi fine funzione inserimento


    echo "<tr><td colspan=2 align=\"center\"><span class=\"testo_blu\"><b>Seleziona $_descrizione da modificare:</b><br></span></td>\n";

    echo "<tr><td colspan=2 align=center><br>";

    if ($_tipo == "tipart")
    {
        tabella_tipart("elenca_select_N", $_codice, "id");
    }
    else
    {
        tabella_catmer("elenca_select_N", $_codice, "id");
    }


    echo "</td></tr>\n";

// PULSANTI E CHIUSURA FORM -----------------------------------------------------------------------------------------
    echo "<td colspan=2 align=center><input type=\"submit\" name=\"azione\" value=\"Modifica !\"></td></tr><tr><td colspan=2 align=center>Oppure inserisci un codice nuovo<br><input type=\"submit\" name=\"azione\" value=\"Inserisci\">\n";
    echo "</form>\n</td>\n";
    echo "</td>\n</tr>\n";
    echo "</table>";
// ************************************************************************************** -->
    echo "</td></tr></table>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>