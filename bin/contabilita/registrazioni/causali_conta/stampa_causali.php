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
ini_set('session.gc_maxlifetime', $SESSIONTIME); 
session_start(); $_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

// aggiungiamo tutte le librerie necessarie
require $_percorso . "librerie/motore_primanota.php";

//carichiamo la base delle pagine:
base_html_stampa("chiudi", $_parametri);

//carichiamo la testata del programma.
#testata_html($_cosa, $_percorso);
//carichiamo il menu a tendina..
#menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['contabilita'] > "1")
{

	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\">";
	echo "<tr>";
	echo "<td width=\"90%\" align=\"center\" valign=\"top\">";
	$_data = date('d-m-Y');
	echo "<span class=\"testo_blu\"><br><b>Stampa causali contabili $azienda $_data</b></span>";


	$result = tabella_causali_contabili("elenco", $_percorso, $_codice, $_parametri);

	// Tutto procede a meraviglia...
	echo "<table width=\"700\">";
	echo "<tr>";
	echo "<td width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Causale</span></td>";
	echo "<td width=\"200\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Descrizione</span></td>";
	echo "<td width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">CONTO_1</span></td>";
	echo "<td width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">CONTO_2</span></td>";
	echo "<td width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">CONTO_3</span></td>";
	echo "<td width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">CONTO_4</span></td>";
	echo "<td width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">CONTO_5</span></td>";

	echo "</tr>";

	foreach ($result['dati'] AS $dati)
	{
		echo "<tr>";
		printf("<td width=\"50\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['causale']);
		printf("<td width=\"250\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['descrizione']);
		printf("<td width=\"50\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['conto_1']);
		printf("<td width=\"50\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['conto_2']);
		printf("<td width=\"50\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['conto_3']);
		printf("<td width=\"50\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['conto_4']);
		printf("<td width=\"50\" align=\"center\"><span class=\"testo_blu\">%s</span></td>", $dati['conto_5']);
		echo "</tr>";
	}

	echo "</td></tr></table></body></html>";
	$conn->null;
	$conn = null;
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>