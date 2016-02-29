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

require_once $_percorso . "librerie/motore_anagrafiche.php";

if ($_SESSION['user']['user'] != "")
{
    base_html("", $_percorso);
    java_script($_cosa, $_percorso);
    jquery_datapicker($_cosa, $_percorso);
    tiny_mce($_cosa, $_percorso);
    echo "</head>";

    testata_html($_cosa, $_percorso);
    menu_tendina($_cosa, $_percorso);


    echo "<table align=\"left\" width=\"100%\">\n";
    echo "<tr><td valign=\"top\" align=\"center\">\n";
    echo "<h3 align=\"center\">Cose Da fare</h3>";

    //$_azione = $_GET['azione'];
    echo "<form action=\"result_todo.php\" method=\"post\">\n";
    if (($_GET['azione'] == "nuova") OR ($_GET['giorno'] != ""))
    {
        echo "<h3 align=\"center\">Nuova Cosa</h3>";
        //creiamo una tabella con l'inserimento deidati..
        $_pulsante_1 = "Annulla";
        $_pulsante_2 = "Inserisci";
        
        $dati['data_start'] = date('Y-m-d');
        $dati['data_end'] = date('Y-m-d');
        $dati['priorita'] = "5";
        $dati['completato'] = "0";
    }
    else
    {
        //andiamo a vidualizzarla...
        //prendiamoci il GET
        $_anno = $_GET['anno'];
        $_numero = $_GET['numero'];

        $dati = tabella_todo("singola", $_anno, $_numero, $_utente_end, $_data_end, $_completato, $_parametri);

        echo $dati['data_start'];
        $_riga = "<tr><td>Numero / anno </td><td align=\"left\"><input type=\"radio\" name=\"numero\" value=\"$dati[numero]\" checked>$dati[numero] / <input type=\"radio\" name=\"anno\" value=\"$dati[anno]\" checked>$dati[anno]</td></tr>\n";
        $_pulsante_1 = "Elimina";
        $_pulsante_2 = "Aggiorna";
    }

    

    echo "<table align=\"left\" class=\"classic\">\n";
    echo $_riga;
    echo "<tr><td colspan=\"2\"><hr></td></tr>\n";
    echo "<tr><td>Destinatari..</td><td align=\"left\">\n";
    
    tabella_utenti(elenca_select_2, $dati['utente_end'], $_user, $_password, $_blocca, $_parametri);
    
    echo "</td></tr>\n";
    echo "<tr><td>Data Partenza</td><td align=\"left\"><input type=\"text\" class=\"data\" name=\"data_start\" value=\"" . cambio_data("it", $dati[data_start]) . "\" size=\"11\" maxlength=\"10\" required > gg-mm-aaaa</td></tr>\n";
    echo "<tr><td>Data Fine</td><td align=\"left\"><input type=\"text\" class=\"data\" name=\"data_end\" value=\"" . cambio_data("it", $dati[data_end]) . "\" size=\"11\" maxlength=\"10\" required > gg-mm-aaaa</td></tr>\n";
    echo "<tr><td>Titolo</td><td align=\"left\"><input type=\"text\" name=\"titolo\" value=\"$dati[titolo]\" size=\"60\" maxlength=\"70\" required></td></tr>\n";
    echo "<tr><td>Completato</td><td align=\"left\"><input type=\"number\" name=\"completato\" value=\"$dati[completato]\" size=\"5\" maxlength=\"3\" min=\"0\" max=\"100\" required> xx%</td></tr>\n";
    echo "<tr><td>Priorità</td><td align=\"left\"><input type=\"number\" name=\"priorita\" value=\"$dati[priorita]\" size=\"5\" maxlength=\"5\" min=\"1\" max=\"9\"> 1-9 il numero più basso più urgente</td></tr>\n";
    echo "<tr><td>Note:</td><td align=\"left\"><textarea name=\"corpo\" style=\"width:100%; height:300px;\">$dati[corpo]</textarea></td></tr>\n";
   
    echo "<tr><td colspan=\"2\"><hr></td></tr>\n";

    echo "<tr><td align=\"center\" colspan=\"2\"> <input type=\"submit\" name=\"azione\" value=\"$_pulsante_2\"> oppure <input type=\"submit\" name=\"azione\" value=\"$_pulsante_1\" onclick=\"if(!confirm('Sicuro di $_pulsante_1 la registrazione ?')) return false;\"></td></tr>\n";

    if ($_pulsante_1 == "Elimina")
    {
        echo "<tr><td align=\"center\" colspan=\"2\"><input type=\"submit\" name=\"azione\" value=\"Annulla\"></td></tr>\n";
    }
    echo "</table>\n";
    echo "</form>\n";
    echo "</td></tr></table></html>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>