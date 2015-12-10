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
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['contabilita'] > "1")
{
//Prendiamoci i post


	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\">";
	echo "<tr>";
	echo "<td width=\"90%\" align=\"center\" valign=\"top\">";

	echo "<span class=\"testo_blu\"><b>Risulati ricerca</b></span>";


// Stringa contenente la query di ricerca...
	$_parametri['descrizione'] = $_POST['descrizione'];
	$_parametri['campi'] = $_POST['campi'];

	$result = tabella_causali_contabili("cerca", $_percorso, $_codice, $_parametri);
	if ($result['errori'] != "")
	{
		echo "Errore.. $result[errori]";
	}
// Tutto procede a meraviglia...
	echo "<table width=\"700\">";
	echo "<tr>";
	echo "<td width=\"50\" align=\"center\" class=\"logo\"><span class=\"testo_bianco\">Causale</span></td>";
	echo "<td width=\"200\" align=\"left\" class=\"logo\"><span class=\"testo_bianco\">Descrizione</span></td>";
	echo "</tr>";

	foreach ($result['dati'] AS $dati)
	{
		echo "<tr>";
		printf("<td width=\"50\" align=\"left\"><span class=\"testo_blu\"><a href=\"maschera_causale.php?azione=Modifica&codice=%s\">%s</span></td>", $dati['causale'], $dati['causale']);
		printf("<td width=\"250\" align=\"left\"><span class=\"testo_blu\"><a href=\"maschera_causale.php?azione=Modifica&codice=%s\">%s</span></td>", $dati['causale'], $dati['descrizione']);

		echo "</tr>";
		echo "<tr>";
		echo "<td width=\"50\" height=\"1\" align=\"center\" class=\"logo\"></td>";
		echo "<td width=\"250\" height=\"1\" align=\"center\" class=\"logo\"></td>";
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