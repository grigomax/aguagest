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

//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

$_SESSION['keepalive'] ++;
//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['setting'] > "3")
{


    printf("<form action=\"user.php\" method=\"POST\">\n");
    echo "<table width=\"400\" border=\"0\" align=center>\n";
    echo "<tr><td colspan=2 align=\"center\"><font size=3><b>Utente</b><br></font></span></td>\n";


// prendo le variabili passate


    if ($_POST['azione'] == "Inserisci")
    {
        if ($_POST['pwd'] == $_POST['repwd'])
        {
            //controllialo se lìutente essite gia

            $check = tabella_utenti("check", $_id, $_POST['user'], $_password, $_blocca, $_parametri);

            if ($check == "SI")
            {
                echo "<td align=\"center\">Utente $_POST[user] Già presente nell'archivio</td>";
                
            }
            else
            {
                //giriamo la data..

                $_POST['datareg'] = cambio_data("us", $_POST['datareg']);

                $inserisci = tabella_utenti("inserisci", $_id, $_user, $_password, $_blocca, $_POST);

                if ($_modifica == "NO")
                {
                    echo "<tr><td align=\"center\">Errore Inserimento Utente</td></tr>";
                }
                else
                {
                    echo "<tr><td align=\"center\">Utente $_POST[user] Inserito correttamente</td></tr>";

                    
                }
            }
        }
        else
        {
            echo "<tr><td align=\"center\">Le password non coincidono si prega di verificare</td></tr>";
        }
        return;
    }// parentesi fine funzione inserimento
    elseif ($_POST['azione'] == "Modifica")
    {

        if ($_POST['pwd'] == $_POST['repwd'])
        {
            $_modifica = tabella_utenti("modifica", $_POST['id'], $_user, $_password, $_blocca, $_POST);

            if ($_modifica == "NO")
            {
                echo "<tr><td align=\"center\">Errore Modifica Utente</td></tr>";
            }
            else
            {
                echo "<tr><td align=\"center\">Utente $_POST[user] moficato correttamente</td></tr>";

                
            }
        }
        else
        {
            echo "<tr><td align=\"center\">Le password non coincidono si prega di verificare</td></tr>";
        }

        return;
    }// parentesi fine funzione inserimento
    elseif ($_POST['azione'] == "Elimina")
    {
        $_elimina = tabella_utenti("elimina", $_POST['id'], $_user, $_password, $_blocca, $_POST);

        if ($_elimina == "NO")
        {
            echo "<tr><td align=\"center\">Errore elimina Utente</td></tr>";
        }
        else
        {
            echo "<tr><td align=\"center\">Utente $_POST[user] eliminato correttamente</td></tr>";

            
        }


        return;
    }// parentesi fine funzione inserimento
    else
    {

        echo "<tr><td colspan=2 align=\"center\"><span class=\"testo_blu\"><b>Inserisci l'utente da modificare:</b><br></span></td>\n";

        echo "<tr><td colspan=2 align=center><br>";

//elenchiamo gli utenti

        tabella_utenti("elenca_select", $_id, $_user, $_password, $_blocca, $_parametri);



// PULSANTI E CHIUSURA FORM -----------------------------------------------------------------------------------------
        echo "<td colspan=2 align=center><input type=\"submit\" name=\"azione\" value=\"Modifica !\"></td></tr>\n";
        echo "<tr><td colspan=2 align=center><hr><br>Oppure inserisci un utente nuovo<br><input type=\"submit\" name=\"azione\" value=\"Inserisci\">\n";
        echo "</form>\n</td>\n";
        echo "</td>\n</tr>\n";
        echo "</table>";
    }
// ************************************************************************************** -->
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>