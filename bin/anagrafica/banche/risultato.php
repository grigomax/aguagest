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
session_start(); $_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";
require $_percorso . "librerie/motore_anagrafiche.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "1")
{


	$_parametri['campi'] = $_POST['campi'];
	$_parametri['descrizione'] = $_POST['descrizione'];


	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\" valign=\"top\">\n";
	echo "<td width=\"90%\" align=\"center\" >\n";
	echo "<span class=\"testo_blu\"><b>Risulati ricerca</b></span>";

	// Stringa contenente la query di ricerca...


	$result = tabella_banche("ricerca", $_codice, $_abi, $_cab, $_parametri);


	// Tutto procede a meraviglia...
	echo "<table width=\"700\">";
	echo "<tr>";
	echo "<td width=\"70\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Codice</span></td>";
	echo "<td width=\"280\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Istituto</span></td>";
	echo "<td width=\"200\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Citta</span></td>";
	echo "<td width=\"150\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Telefono</span></td>";
	echo "<td width=\"150\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">abi</span></td>";
	echo "<td width=\"150\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Cab</span></td>";
	echo "</tr>";

	foreach ($result AS $dati)
	{
		echo "<tr>";
		printf("<td width=\"70\" align=\"center\"><span class=\"testo_blu\"><a href=\"visualizzabanca.php?codice=%s\">%s</span></td>", $dati['codice'], $dati['codice']);
		printf("<td width=\"280\" align=\"left\"><span class=\"testo_blu\"><a href=\"visualizzabanca.php?codice=%s\">%s</span></td>", $dati['codice'], $dati['banca']);
		printf("<td width=\"200\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['indirizzo']);
		printf("<td width=\"150\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['telefono']);
		printf("<td width=\"150\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['abi']);
		printf("<td width=\"150\" align=\"left\"><span class=\"testo_blu\">%s</span></td>", $dati['cab']);
		echo "</tr>";
		echo "<tr>";
		echo "<td width=\"70\" height=\"1\" align=\"center\" class=\"logo\"></td>";
		echo "<td width=\"280\" height=\"1\" align=\"center\" class=\"logo\"></td>";
		echo "<td width=\"200\" height=\"1\" align=\"center\" class=\"logo\"></td>";
		echo "<td width=\"150\" height=\"1\" align=\"center\" class=\"logo\"></td>";
		echo "<td width=\"150\" height=\"1\" align=\"center\" class=\"logo\"></td>";
		echo "<td width=\"150\" height=\"1\" align=\"center\" class=\"logo\"></td>";
		echo "</tr>";
	}
	echo "</td></tr></table></body></html>";

	//chiudiamo le connessioni
	$conn->null;
	$conn = null;
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>
