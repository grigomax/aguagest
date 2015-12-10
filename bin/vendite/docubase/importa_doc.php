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

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

require $_percorso . "librerie/motore_anagrafiche.php";
require $_percorso . "librerie/motore_doc_pdo.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "1")
{


#poi mi prendo i get, in base al get passato, so quale documento da dove a dove
	$_start = $_GET['start'];
	$_end = $_GET['end'];

	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";
	echo "<br><form action=\"filtro_doc.php\" method=\"POST\">\n";
//in base al documento selezionato mi dice il tipo di importazione
	selezione_documento($_start, $_end);

	echo "<tr><td align=center><br>";
	
	if ($_start == "fornitore")
	{
		tabella_fornitori("elenca_select", "utente", $_parametri);
	}
	else
	{
		tabella_clienti("elenca_select", "utente", $_parametri);
	}

	echo "</table><center><br><input type=\"submit\" name=\"azione\" value=\"Annulla\">&nbsp;<input type=\"submit\" value=\"Avanti\">\n";
	echo "</form>\n</td>\n";
	echo "</td>\n</tr>\n";
	echo "</body></html>";
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>