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

	if ($_POST['anno'] == "")
	{
		$_anno = date('Y') - 1;
	}
	else
	{
		$_anno = $_POST['anno'];
	}



	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\">";
	echo "<tr>";

	echo "<td>";

	echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";
	echo "<h2>Stampa Partitari / Mastrini</h2>";
	echo "<h4>Per poter procedere alla stampa dei partitari,<br> deve essere stato consolidato il libro giornale<br>ATTENZIONE CI VORRA' MOLTO TEMPO ANCHE 10 min.</h4>\n";

	echo "<form action=\"libro_partitari.php\" method=\"POST\">\n";
	echo "Seleziona anno => <input type=\"number\" size=\"6\" maxlength=\"4\" name=\"anno\" value=\"$_anno\"><input type=\"submit\" name=\"cambia\">\n";

	echo "</form>\n";

	echo "<h4>Verifica eventuali registrazioni aperte..</h4>\n";
	$query = "SELECT anno, giornale, status from prima_nota where status != 'chiuso' AND anno='$_anno'";

	$result = mysql_query($query, $conn);

	$righe = mysql_num_rows($result);

	if ($righe > "0")
	{
		//vuol dire che ci sono ancora delle registrazioni aperte e il libro giornale non è stato correttamente chiuso o finito
		echo "<h4>Attenzione l'anno selezionato contiene ancora registrazioni aperte..</h4>\n";
	}
	else
	{
		echo "<form action=\"libro_partitari_pdf.php\" method=\"POST\">\n";

		echo "<h4>E possibile stampare i partitari per l'anno = <input type=\"radio\" name=\"anno\" value=\"$_anno\" checked>$_anno &nbsp;</h4>\n";
		echo "<h4>La stampa in formato pdf verr&agrave; inviata in linea al Browser</h4>\n";


		echo "<br><br><input type=\"submit\" name=\"azione\" value=\"Stampa\">\n";
	}






	echo "</td>\n";
	echo "</form>\n";
	echo "</table></body></html>";
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>