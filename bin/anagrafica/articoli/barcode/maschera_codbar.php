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
require $_percorso . "librerie/motore_anagrafiche.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "3")
{
    echo "<table width=\"80%\" align=\"center\">\n";

    echo "<tr><td width=\"80%\" align=\"center\" valign=\"top\">\n";


    if ($_GET['azione'] == "nuovo")
    {
        echo "<h2>Inserisci nuovo codice associato</h2>\n";

        echo " <h4>Seleziona Articolo</h4>\n";


        echo "<form action=\"result_barcode.php\" name=\"result\" method=\"POST\">\n";

        if ($_GET['articolo'] != "")
        {
            echo "<br>\n";
            echo "<input type=\"radio\" name=\"articolo\" value=\"$_GET[articolo]\" checked>$_GET[articolo]\n";
            echo "<br><br>\n";
        }
        else
        {

            echo "<select name=\"articolo\">\n";
            echo "<option value=\"\"> </option>\n";

//leggiamo gli articoli con la suo funzione

            $result = tabella_articoli("elenco_select", $_codice, $_parametri);

            foreach ($result AS $dati)
            {
                echo "<option value=\"$dati[articolo]\">$dati[articolo] - $dati[descrizione] </option>\n";
            }

            echo "</select>\n";
        }

        echo "<br>\n";
        echo "<h4>Inserisci il codice a barre relativo all'articolo </h4>\n";
        echo "<input type=\"text\" name=\"codbar\" size=\"33\" maxlength=\"30\">\n";

        echo "<br><br>\n";

        
        //pulsanti("salva", "submit", "result" , $_formmethod, $_formaction, "40px", "40px", "Inserisci", "azione", "Inserisci", "inserisci", $_id);
        
        echo "<input type=\"submit\" name=\"azione\" value=\"Inserisci\">\n";
    }
    elseif ($_GET['azione'] == "cerca")
    {

        echo "<h2>Cerca codice a barre</h2>\n";

        echo " <h4>Seleziona Articolo</h4>\n";


        echo "<form action=\"maschera_codbar.php\" method=\"GET\">\n";

        echo "<select name=\"articolo\">\n";
        echo "<option value=\"\"> </option>\n";

//leggiamo gli articoli con la suo funzione

        $result = tabella_articoli("elenco_select", $_codice, $_parametri);

        foreach ($result AS $dati)
        {
            echo "<option value=\"$dati[articolo]\">$dati[articolo] - $dati[descrizione] </option>\n";
        }

        echo "</select>\n";

        echo "<br>\n";
        echo "<h4>Oppure batti il codice a barre relativo all'articolo </h4>\n";
        echo "<input type=\"text\" name=\"codbar\" size=\"33\" maxlength=\"30\">\n";

        echo "<br><br>\n";

        echo "<input type=\"submit\" name=\"azione\" value=\"Elenca\">\n";
    }
    else
    {
        echo "<h2>Elenco codice a barre selezionato per articolo</h2>\n";

        $result = tabella_barcode("elenco_codice", $_GET['codbar'], $_GET['articolo'], $_rigo);

        echo "<table border=\"0\" width=\"100%\"> \n";
        echo "<tr><td colspan=\"4\"> Selezionare il codice da modificare </td></tr>\n";
        echo "<tr><td>Rigo</td><td>codice articolo </td><td>Codice a barre</td><td>Azione</td></tr>\n";

        foreach ($result AS $dati)
        {
            echo "<form action=\"result_barcode.php\" method=\"POST\">\n";

            if ($dati['rigo'] == "1")
            {
                echo "<tr><td><input type=\"radio\" name=\"rigo\" value=\"$dati[rigo]\" checked> $dati[rigo] </td><td><input type=\"radio\" name=\"articolo\" value=\"$dati[articolo]\" checked> $dati[articolo] </td><td><input type=\"radio\" name=\"codbar\" value=\"$dati[codbar]\" checked> $dati[codbar]</td><td>Non Modificabile</td></tr>\n";
            }
            else
            {
                echo "<tr><td><input type=\"radio\" name=\"rigo\" value=\"$dati[rigo]\" checked> $dati[rigo] </td><td><input type=\"radio\" name=\"articolo\" value=\"$dati[articolo]\" checked> $dati[articolo] </td><td><input type=\"radio\" name=\"codbar\" value=\"$dati[codbar]\" checked> $dati[codbar]</td><td>  <input type=\"submit\" name=\"azione\" value=\"Modifica\"><input type=\"submit\" name=\"azione\" value=\"Elimina\"></td></tr>\n";
            }


            echo "</form>\n";
        }

        echo "</table>\n";


        // qui facciamo una serie di input ognuno con un campo diverso..
    }


    echo "</td>	</tr></table></body></html>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>