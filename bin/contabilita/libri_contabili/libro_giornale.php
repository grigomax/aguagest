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
base_html("", $_percorso);
java_script($_cosa, $_percorso);

jquery_datapicker($_cosa, $_percorso);

echo "</head>\n";
//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['contabilita'] > "1")
{

	if ($_POST['anno'] == "")
	{
		$_anno = date('Y');
	}
	else
	{
		$_anno = $_POST['anno'];
	}


	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\">";
	echo "<tr>";

	echo "<td>";

	echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";
	echo "<h2>Stampa libro giornale</h2>";
	echo "<h4>La stampa in formato pdf verr&agrave; inviata in linea al Browser</h4>\n";

	echo "<form action=\"libro_giornale.php\" method=\"POST\">\n";
	echo "Cambia anno => <input type=\"number\" size=\"6\" maxlength=\"4\" name=\"anno\" value=\"$_anno\"><input type=\"submit\" name=\"cambia\">\n";

	echo "</form>\n";

//verifichiamo che il giornale non sia già stato chiuso..
	$query = "SELECT anno, status FROM prima_nota WHERE anno = '$_anno' and status != 'chiuso'";
	$result = mysql_query($query, $conn);

	#$dati = mysql_fetch_array($result);
	
	if (mysql_num_rows($result) > 0)
	{

		$query = "SELECT giornale, SUM(DARE) AS dare from prima_nota where status = 'chiuso' AND anno='$_anno' AND giornale != ''";
		$result = mysql_query($query, $conn);

		$dati_val = mysql_fetch_array($result);

		$query = "SELECT giornale from prima_nota where status = 'chiuso' AND  anno='$_anno' ORDER BY giornale DESC limit 1";
		$result = mysql_query($query, $conn);

		$dati = mysql_fetch_array($result);

		echo "<form action=\"libro_giornale_pdf.php\" method=\"POST\">\n";

		echo "<h4>Ultimo numero = <input type=\"radio\" name=\"prog\" value=\"$dati[giornale]\" checked>$dati[giornale] &nbsp; Valore = <input type=\"radio\" name=\"valore\" value=\"$dati_val[dare]\" checked>$dati_val[dare] </h4>\n";

		echo "<br><br>Data di partenza = <input type=\"text\" name=\"data_start\" class=\"data\" size=\"11\" maxlength=\"10\" value=\"\">\n";
		echo "Data finale = <input type=\"text\" name=\"data_end\" class=\"data\" size=\"11\" maxlength=\"10\" value=\"\">\n";

		echo "<br><br><input type=\"submit\" name=\"azione\" value=\"Stampa\">\n";
	}
	else
	{
		echo "<h4>La stampa del giornale per l'anno $_anno &egrave; gi&agrave; stata stampata in definitivo</h4>\n";
	}


	echo "</td></tr>\n";
	echo "</form>\n";
	echo "</table></body></html>";
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>