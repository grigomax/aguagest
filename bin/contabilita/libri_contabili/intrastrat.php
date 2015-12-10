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
session_start();
$_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);
require "../../../setting/par_conta.inc.php";

//carichiamo la base delle pagine:
base_html("", $_percorso);
java_script($_cosa, $_percorso);
echo "</head>";
jquery_datapicker($_cosa, $_percorso);
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
    echo "<h3>Stampa Intrastrat - Comunitario</h3>";
    echo "<p><font type=\"arial\" size=\"2\">La stampa prenderà in considerazione tutte le aliquote iva che avranno la S <br>
    sulla sezione iva CEE</p>\n";

    echo "<form action=\"stampa_intrastrat.php\" method=\"POST\" target=\"_blank\">\n";

    echo "<br><br>Data di partenza = <input type=\"text\" name=\"data_start\" class=\"data\" size=\"11\">\n";
    echo "Data finale = <input type=\"text\" name=\"data_end\" class=\"data\" size=\"11\">\n";

    echo "<br><br><input type=\"submit\" name=\"azione\" value=\"Stampa\">\n";

    echo "</body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>