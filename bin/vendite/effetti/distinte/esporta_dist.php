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
session_start(); $_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

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
				<span class="intestazione"><b>Scegliere la distinta da Esportare</b><br></span><br>
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


		echo "<form action=\"esporta_dist.php\" method=\"POST\">\n";
		echo "Cambia anno => <input type=\"number\" size=\"6\" maxlength=\"4\" name=\"anno\" value=\"$_anno\"><input type=\"submit\" name=\"cambia\">\n";

		echo "</form>\n";

		if ($CONTABILITA == "SI")
		{
			echo "<tr><td align=center><font color=\"red\">ATTENZIONE le distinte inserite in contabilit&agrave; non possono essere esportate</font></td></tr>\n";
		}

		printf("<form action=\"esporta_dist2.php\" method=\"GET\">");

		echo "<tr><td align=\"center\" colspan=\"2\"><input type=\"radio\" name=\"anno\" value=\"$_anno\" checked >$_anno</td></tr>";

		echo "<tr><td align=center colspan=\"2\"><br>";
		echo "<select name=\"ndistinta\">\n";
		echo "<option value=\"\"></option>";


		// Stringa contenente la query di ricerca... 
		$query = "select annoeff, ndistinta, datadist, bancadist, banca, status from effetti INNER JOIN banche ON effetti.bancadist = banche.codice where datadist LIKE '$_anno%' GROUP BY ndistinta order by ndistinta desc ";

		#echo $query;
		// Esegue la query...
		$result = $conn->query($query);
		if ($conn->errorCode() != "00000")
		{
			$_errore = $conn->errorInfo();
			echo $_errore['2'];
			//aggiungiamo la gestione scitta dell'errore..
			$_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
			$_errori['files'] = "ristampa dist";
			scrittura_errori($_cosa, $_percorso, $_errori);
		}

		echo "<span class=\"testo_blu\">";
		foreach ($result AS $dati)
		{
			printf("<option value=\"%s%s\">%s - %s - %s- %s</option>\n", $dati['datadist'], $dati['ndistinta'], $dati['ndistinta'], $dati['datadist'], $dati['banca'], $dati['status']);
		}

		echo "</select>\n";
		echo "</td></tr>\n";

		echo "<tr><td align=center colspan=2>";
		echo "<br><br><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" name=\"azione\" value=\"Esporta\">";
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