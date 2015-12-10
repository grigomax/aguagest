<?php
/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../../";
require $_percorso ."../setting/vars.php";
ini_set('session.gc_maxlifetime', $SESSIONTIME); 
session_start(); $_SESSION['keepalive']++;
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



if ($_SESSION['user']['contabilita'] > "1")
{
    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\">";
    echo "<tr>";

    echo "<td>";

    echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";
    echo "<h2>Stampa Intestazione libri fiscali</h2>";
    echo "<h4>La stampa in formato pdf verr&agrave; inviata in linea al Browser</h4>\n";

    echo "<form action=\"stampa_int_pdf.php\" method=\"POST\">\n";
    echo "<select name=\"libro\">\n";
    echo "<option value=\"\">Scegli il libro</option>\n";
    echo "<option value=\"LG\">Libro Giornale</option>\n";
    echo "<option value=\"IA\">Libro Iva Acquisti</option>\n";
    echo "<option value=\"IV\">Libro Iva Vendite</option>\n";
    echo "</select>\n";

    $_anno = date('Y');

    echo "<br><br>Scegliere l'anno = <input type=\"number\" name=\"anno\" value=\"$_anno\">\n";
    echo "<br><br>Scegliere il numero di partenza = <input type=\"number\" name=\"numero_start\" value=\"1\">\n";
    echo "<br><br>Scegliere il numero di pagine da stampare = <input type=\"number\" name=\"pagine\" value=\"1\">\n";

    echo "<br><br><input type=\"submit\" name=\"azione\" value=\"Stampa\">\n";



    echo "</td>\n";
    echo "</form>\n";
    echo "</table></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>