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
ini_set('session.gc_maxlifetime', $SESSIONTIME);
session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";
require "../../librerie/motore_anagrafiche.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);



//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "1")
{

	java_script($_cosa, $_percorso);

	jquery_datapicker($_cosa, $_percorso);

	echo "</head>\n";

	echo "<body>";

	echo "<table align=\"center\" width=\"80%\" border=\"0\">";
	echo "<tr><td align=\"center\" valign=\"center\">";
	echo "<span class=\"testo_blu\"><center><h2><b>Genera Effetti di Portafoglio</h2></b>";
	echo "</td>";
	echo "<tr><td align=\"center\" valign=\"center\">";
	echo "<span class=\"testo_blu\"><center><h3>Seleziona le fatture da esportare</h3>";
	echo "</td>";
	echo "</table>";


// chiediamo i dati..

	$result = tabella_effetti("elenco_fatture", $_percorso, $_annoeff, $_numeff, $_parametri);

// Tutto procede a meraviglia...
	echo "<table width=\"80%\" align=\"center\" border=\"0\">";
	echo "<tr>";
	echo "<form action=\"genera_eff2.php\" method=\"POST\">\n";
	echo "<td width=\"100\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Data</span></td>";
	echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Numero</span></td>";
	echo "<td width=\"40\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Pagamento</span></td>";
	echo "<td width=\"40\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Codice</span></td>";
	echo "<td width=\"400\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Ragione Sociale</span></td>";
	echo "<td width=\"50\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Valore</span></td>";
	echo "<td width=\"80\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Seleziona</span></td>";

	echo "</tr>";

// azzero la variabile
	$_valoretot = 0;
	foreach ($result AS $dati)
	{

		$_codcli = $dati['codice'];

		if ($_prima != "NO")
		{
			$_codcli1 = $_codcli;
		}

		if ($_codcli1 != $_codcli)
		{
			echo "<tr><td></td><td></td><td></td><td></td><td align=\"right\"><font size=\"2\">Valore ivato == </font>  </td><td> <font size=\"2\">$_valoretot</font> </td><td></td><td></td></tr>";
			echo "<tr>";
			echo "<td width=\"100\" height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td width=\"40\" height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td width=\"40\" height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td width=\"400\" height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "<td width=\"80\" height=\"1\" align=\"center\" class=\"logo\"></td>";
			echo "</tr>";
//azzero le variabili
			$_valoretot = 0;
			echo "<tr>";

			printf("<td width=\"100\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['datareg']);
			printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\"><b>%s</b></span></td>", $dati['ndoc']);
			printf("<td width=\"40\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['modpag']);
			printf("<td width=\"40\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['codice']);
			printf("<td width=\"400\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['ragsoc']);
			printf("<td width=\"50\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['totdoc']);
			printf("<td width=\"30\" align=\"center\"><input type=checkbox name=\"numero[]\" value=\"%s%s\"></td>\n", $dati['anno'], $dati['ndoc']);
			echo "</tr>";
			$_valoretot = $_valoretot + $dati['totdoc'];
			$_codcli1 = $_codcli;
		}
		else
		{
			echo "<tr>";
			printf("<form action=\"genera_eff2.php\" method=\"POST\">", $dati['anno']);
			printf("<td width=\"100\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['datareg']);
			printf("<td width=\"80\" align=\"center\"><span class=\"testo_blu\"><b>%s</b></span></td>", $dati['ndoc']);
			printf("<td width=\"40\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['modpag']);
			printf("<td width=\"40\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['codice']);
			printf("<td width=\"400\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['ragsoc']);
			printf("<td width=\"50\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['totdoc']);
			printf("<td width=\"30\" align=\"center\"><input type=checkbox name=\"numero[]\" value=\"%s%s\"></td>\n", $dati['anno'], $dati['ndoc']);
			echo "</tr>";
			$_valoretot = $_valoretot + $dati['totdoc'];
			$_codcli1 = $_codcli;
			$_prima = "NO";
		}
	}

	echo "</td></tr>\n";
	$_data = date('d-m-Y');
	echo "<tr><td colspan=\"7\"><hr></td></tr>\n";
	echo "<tr><td colspan=\"7\" align=\"right\" class=\"testo_blu\">Genera Effetti con data = <input type=\"text\" class=\"data\" size=\"11\" maxlength=\"10\" name=\"data\" value=\"$_data\"><input type=\"submit\" name=\"azione\" value=\"vai\"></td>";

	echo "</form></td></tr></table></body></html>";
	//chiudiamo le connessione
	$conn =null;
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>