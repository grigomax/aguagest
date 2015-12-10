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

    if ($_POST['anno'] == "")
    {
	$_anno = date('Y');
    }
    else
    {
	$_anno = $_POST['anno'];
    }

    echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";
    echo "<h2>Controllo Quadraura Registrazioni anno $_anno</h2>";

    echo "<form action=\"controllo_Q.php\" method=\"POST\">\n";
    echo "Cambia anno => <input type=\"number\" size=\"6\" maxlength=\"4\" name=\"anno\" value=\"$_anno\"><input type=\"submit\" name=\"cambia\">\n";

    echo "</form>\n";


    $query = "SELECT SUM(dare) AS dare, SUM(avere) AS avere from prima_nota where anno='$_anno'";

    $result = mysql_query($query, $conn);

    $dati = mysql_fetch_array($result);

    $_sbilanciamento = $dati['dare'] - $dati['avere'];

    echo "<h4><font color=\"red\">Controllo sul totale.. $_sbilanciamento</font></h4>";

    echo "<h4>Di seguito elencate le registrazioni fuori quadratura..</h4>\n";

    $query = "SELECT *, (SUM(dare) - SUM(avere)) AS diff from prima_nota where anno='$_anno' GROUP BY nreg ORDER BY nreg";

    $result = mysql_query($query, $conn);

//apriamo una tabella..

    echo "<table border=\"1\" width=\"80%\" align=\"center\">\n";
    echo "<td class=\"tabella\">N. Reg.</td>\n";
    echo "<td class=\"tabella\">Data</td>\n";
    echo "<td class=\"tabella\">Causale</td>\n";
    echo "<td class=\"tabella\">Sbilancio</td></tr>\n";

    while ($dati = mysql_fetch_array($result))
    {
	if ($dati['diff'] != 0.00)
	{
	    printf("<form action=\"visualizza_reg.php?causale=%s&anno=%s\" method=\"POST\">\n", $dati['causale'], $dati['anno']);
	    printf("<td width=\"30\" align=\"center\" class=\"tabella_elenco\"><input type=\"submit\" name=\"nreg\" value=\"%s\"></span></td>\n", $dati['nreg']);
	    echo "<td class=\"tabella_elenco\">$dati[data_reg]</td>\n";
	    echo "<td class=\"tabella_elenco\">$dati[causale]</td>\n";
	    echo "<td class=\"tabella_elenco\">$dati[diff]</td></tr>\n";
	    echo "</form>\n";
	}
    }

    echo "</td>\n";
    echo "</table></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>