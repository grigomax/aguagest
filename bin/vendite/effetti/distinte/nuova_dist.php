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
session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";
require "../../../librerie/motore_anagrafiche.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

//carichiamo la base delle pagine:
base_html("", $_percorso);

java_script($_cosa, $_percorso);

jquery_datapicker($_cosa, $_percorso);

echo "</head>\n";
//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "2")
{
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\">\n";
	echo "<tr>\n";
	echo "<td>\n";
	
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\">\n";
	echo "<tr>\n";
	echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";
	echo "<span class=\"intestazione\">Crea nuova distinta <br><b>Scegliere la banca</b></span><br></td></tr>\n";

	$_date = date('d-m-Y');

	echo "<br><br><form action=\"sel_eff.php\" method=\"POST\">";
	echo "<tr><td align=center><br>";
	echo "Seleziona la data di creazione <input type=\"text\" name=\"date\" class=\"data\" value=\"$_date\" size=\"11\" maxlength=\"10\"><br><br>\n";
	echo "<select name=\"banca\">\n";
	echo "<option value=\"\"></option>";

	$result = tabella_banche("elenca", $_codice, $_abi, $_cab, $_parametri);

	// Stringa contenente la query di ricerca... solo dei fornitori
	$query = sprintf("select * from banche order by banca");


	// Tutto procede a meraviglia...
	echo "<span class=\"testo_blu\">";
	foreach ($result AS $dati)
	{
		printf("<option value=\"%s\">%s - %s</option>\n", $dati['codice'], $dati['codice'], $dati['banca']);
	}

	echo "</select>\n";
	echo "</td></tr>\n";

	echo "</table><center><br><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" value=\"Avanti\">\n";
	echo "</form>\n</td>\n";
	echo "</td>\n</tr></table></td></tr>\n";
	echo "</table></body></html>";
	
	$conn->null;
	$conn = null;
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>