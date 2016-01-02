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

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


//carichiamo le librerie necessarie..
require $_percorso . "librerie/motore_anagrafiche.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "1")
{

	echo "<table width=\"80%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";
	echo "<tr><td align=\"center\" valign=\"top\" colspan=\"2\">\n";
	echo "<span class=\"intestazione\"><b>Scegliere la distinta da modificare</b><br></span><br>\n";
	echo "</td></tr>\n";

	if ($_POST['anno'] == "")
	{
		$_anno = date('Y');
	}
	else
	{
		$_anno = $_POST['anno'];
	}


	$_parametri['anno'] = $_anno;
        $_parametri['contabilita'] = "SI";
        $_parametri['azione'] = "modifica";
	//selezioniamo le distinte..
	$result = tabella_effetti("elenco_distinta", $_percorso, $_annoeff, $_numeff, $_parametri);

	echo "<form action=\"modifica_dist.php\" method=\"POST\">\n";
	echo "Cambia anno => <input type=\"number\" size=\"6\" maxlength=\"4\" name=\"anno\" value=\"$_anno\"><input type=\"submit\" name=\"cambia\">\n";

	echo "</form>\n";


	if ($CONTABILITA == "SI")
	{
		echo "<tr><td align=center><font color=\"red\">ATTENZIONE le distinte inserite in contabilit&agrave; non possono essere modificate</font></td></tr>\n";
	}

	printf("<br><form action=\"modifica_dist2.php\" method=\"POST\">");

	echo "<tr><td align=center>Anno Corrente<input type=\"radio\" name=\"anno\" value=\"$_anno\" checked>$_anno</td>\n";

	echo "<tr><td align=center colspan=\"2\"><br>";
	echo "<select name=\"ndistinta\">\n";
	echo "<option value=\"\"></option>";

	// Tutto procede a meraviglia...
	echo "<span class=\"testo_blu\">";
	foreach ($result AS $dati)
	{
		printf("<option value=\"%s\">%s - %s - %s- %s</option>\n", $dati['ndistinta'], $dati['ndistinta'], $dati['datadist'], $dati['banca'], $dati['status']);
	}

	echo "</select>\n";
	echo "</td></tr>\n";

	echo "<tr><td align=center colspan=2>";
	echo "<br><br><input type=\"reset\" value=\"Annulla\">&nbsp;<input type=\"submit\" name=\"azione\" value=\"modifica\"> &nbsp;<input type=\"submit\" name=\"azione\" value=\"elimina\">";
	echo "</td></tr>";
	echo "</form></table>";

	echo "</body></html>";

	$conn->null;
	$conn = null;
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>