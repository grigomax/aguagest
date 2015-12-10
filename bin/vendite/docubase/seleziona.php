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

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

require "../../librerie/motore_doc_pdo.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
#menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "1")
{

//recupero le session

	$_codutente = $_SESSION['utente'];
	$_tdoc = $_SESSION['tdoc'];
	$dati = $_SESSION['datiutente'];
#echo $_SESSION['programma'];
//controllo che il campo codutente sia pieno
	if ($_codutente == "")
	{
		echo "<h2>ATTENZIONE NESSUN UTENTE SELEZIONATO<h2>\n";
		echo "<h3>Si prega di tornare indietro e verificare</h3>\n";
		exit;
	}


	intesta_html($_tdoc, "normale", $dati, "");


	if ($_SESSION['programma'] == "ACQUISTO")
	{
		schermata_seleziona("ord_for", $_tdoc);
	}
	elseif ($_SESSION['programma'] == "DDT_ACQ")
	{
		schermata_seleziona("ddt_acq", $_tdoc);
	}
	else
	{
		schermata_seleziona("vendita", $_tdoc);
	}

	echo "<br>\n";

	printf("<form action=\"annulladoc.php\" method=\"POST\">");
	printf("<table><tr><td colspan=\"10 \" align=\"center\" class=\"testo_blu\"><br>Per annullare l'operazione  <input type=\"submit\" name=\"azione\" value=\"annulla\"></form></td>");
	printf("</tr>");
	echo "</table></body></html>";
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>