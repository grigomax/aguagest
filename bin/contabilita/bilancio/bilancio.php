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

//carichiamo la base delle pagine:
base_html("", $_percorso);
java_script($_cosa, $_percorso);

jquery_datapicker($_cosa, $_percorso);

echo "</head>";
//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['contabilita'] > "1")
{


    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">";
    echo "<tr>";

    echo "<td>";

    echo "<td align=\"center\" valign=\"top\">\n";
    echo "<h2>Visualizza e stampa Bilancio</h2>";

    echo "</td></tr>\n";

    echo "<form action=\"bilancio_2.php\" method=\"POST\">\n";

    echo "<table align=\"center\" border=\"0\" width=\"50%\">\n";
    echo "<tr>\n";
    echo "<td align=\"right\">Data di Contabile di partenza = </td><td><input type=\"text\" class=\"data\" name=\"data_start\" size=\"11\" maxlength=\"10\" value=\"\"></td></tr>\n";
    echo "<tr><td align=\"right\">Data finale = </td><td><input type=\"text\" class=\"data\" name=\"data_end\" size=\"11\" maxlength=\"10\" value=\"\"></td></tr>\n";

    echo "<tr><td colspan=\"2\" align=\"center\" ><br><br><input type=\"submit\" name=\"azione\" value=\"Visualizza\"></td></tr>\n";

    echo "</table>\n";
    echo "</td>\n";
    echo "</form>\n";
    echo "</table></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>