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

//carichiamo le librerie necessarie allo svolgimento
require $_percorso . "librerie/motore_anagrafiche.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "2")
{

	?>
	<br>
	<table width="80%" cellspacing="0" cellpadding="0" border="0" align="center">
		<tr>
			<td align="center" valign="top" colspan="2">
				<span class="intestazione"><b>Scegliere la distinta da Ristampare</b><br></span><br>
			</td></tr>
		<?php
		if ($_POST['anno'] == "")
		{
			$_anno = date('Y');
		}
		else
		{
			$_anno = $_POST['anno'];
		}

		//selezioniamo le distinte da mostrare
		$_parametri['anno'] = $_anno;
                //gli diciamo di non guardare la contabilita..
                $_parametri['contabilita'] = "NO";
		$result = tabella_effetti("elenco_distinta", $_percorso, $_annoeff, $_numeff, $_parametri);

		echo "<form action=\"ristampa_dist.php\" method=\"POST\">\n";
		echo "Cambia anno => <input type=\"number\" size=\"6\" maxlength=\"4\" name=\"anno\" value=\"$_anno\"><input type=\"submit\" name=\"cambia\">\n";

		echo "</form>\n";

		printf("<form action=\"stampa_dist.php\" target=\"_blank\" method=\"GET\">");

		echo "<tr><td align=\"center\" colspan=\"2\"><input type=\"radio\" name=\"anno\" value=\"$_anno\" checked >$_anno</td></tr>";

		echo "<tr><td align=center colspan=\"2\"><br>";
		echo "<select name=\"ndistinta\">\n";
		echo "<option value=\"\"></option>";
		echo "<span class=\"testo_blu\">";
		foreach ($result AS $dati)
		{
			printf("<option value=\"%s%s\">%s - %s - %s- %s</option>\n", $dati['datadist'], $dati['ndistinta'], $dati['ndistinta'], $dati['datadist'], $dati['banca'], $dati['status']);
		}

		echo "</select>\n";
		echo "</td></tr>\n";

		echo "<tr><td align=center colspan=2>";
		echo "<br><br><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" name=\"azione\" value=\"Stampa\"> \n";
		echo "</td></tr>";
		echo "</table>\n";
		echo "</form>\n";

		echo "</body></html>";
		$conn->null;
		$conn = null;
	}
	else
	{
		permessi_sessione($_cosa, $_percorso);
	}
	?>