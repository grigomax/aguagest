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
	// Inizio tabella pagina principale ----------------------------------------------------------
	echo "<table width=\"100%\" border=0 cellspacing=\"0\" align=\"left\" cellpadding=\"4\">\n";

	echo "<tr>";
	echo "<td width=\"85%\" align=\"center\" valign=\"top\" class=\"foto\">\n";

	echo "<span class=\"testo_blu\"><br><b>Visualizzazione Breve ISTITUTO</b></span><br><br>";

	// mi prendo il GET appena passato

	$_codice = $_GET['codice'];

	$dati = tabella_banche("singola", $_GET['codice'], $_abi, $_cab, $_parametri);

	if($dati == "errore")
	{
		echo "<h2>Si è verificato un errore..</h2>\n";
		echo "<h2> ".$result[errori][descrizione] ."\n";
		exit;
	}
	

		echo "<form action=\"modificabanca.php?azione=modifica\" method=\"POST\">";
		echo "<table width=\"80%\" border=\"0\">";
// CAMPO Articolo ---------------------------------------------------------------------------------------
		echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Codice:&nbsp;</b></span></td>\n";
		printf("<td align=\"left\"><b>%s</b></td><tr>\n", $dati['codice']);

                echo "<tr><td align=\"left\" width=\"100\"><span class=\"testo_blu\"><b>Ultima modifica :&nbsp;</b></span></td>";
		printf("<td align=\"left\"><b>%s</b></td></tr>\n", $dati['ts']);
// CAMPO Descrizione ---------------------------------------------------------------------------------------
		echo "<tr><td align=\"left\" width=\"100\"><span class=\"testo_blu\"><b>Istituto soc. :&nbsp;</b></span></td>";
		printf("<td align=\"left\"><b>%s</b></td></tr>\n", $dati['banca']);

// CAMPO iva -----------------------------------------------------------------------------------------
		echo "<tr><td align=\"left\"><span class=\"testo_blu\">Indirizzo:&nbsp;</span></td>";
		printf("<td align=\"left\">%s</td></tr>", $dati['indirizzo']);


// ---------------------------------------------------------------------------------------
		echo "<tr><td align=\"left\"><span class=\"testo_blu\">Telefono.:&nbsp;</span></td>";
		printf("<td align=\"left\">%s</td></tr>", $dati['telefono']);

// ---------------------------------------------------------------------------------------
		echo "<tr><td  align=\"left\"><span class=\"testo_blu\">Cell .:&nbsp;</span></td>";
		printf("<td align=\"left\">%s</td></tr>", $dati['cell']);

// ---------------------------------------------------------------------------------------
		echo "<tr><td  align=\"left\"><span class=\"testo_blu\">Fax.:&nbsp;</span></td>";
		printf("<td align=\"left\">%s</td></tr>", $dati['fax']);

// ---------------------------------------------------------------------------------------
		echo "<tr><td align=\"left\"><span class=\"testo_blu\">Abi.:&nbsp;</span></td>";
		printf("<td align=\"left\">%s</td></tr>", $dati['abi']);

// ---------------------------------------------------------------------------------------
		echo "<tr><td  align=\"left\"><span class=\"testo_blu\">Cab.:&nbsp;</span></td>";
		printf("<td align=\"left\">%s</td></tr>", $dati['cab']);

// ---------------------------------------------------------------------------------------
		echo "<tr><td align=\"left\"><span class=\"testo_blu\">Cin .:&nbsp;</span></td>";
		printf("<td align=\"left\">%s</td></tr>", $dati['cin']);

// ---------------------------------------------------------------------------------------
		echo "<tr><td align=\"left\"><span class=\"testo_blu\">C/C :&nbsp;</span></td>";
		printf("<td align=\"left\">%s</td></tr>", $dati['cc']);

// ---------------------------------------------------------------------------------------
		echo "<tr><td  align=\"left\"><span class=\"testo_blu\">Iban.:&nbsp;</span></td>";
		printf("<td align=\"left\">%s</td></tr>", $dati['iban']);

// ---------------------------------------------------------------------------------------
		echo "<tr><td align=\"left\"><span class=\"testo_blu\">Swift / Bic .:&nbsp;</span></td>";
		printf("<td align=\"left\">%s</td></tr>", $dati['swift']);

// CAMPO note articolo -----------------------------------------------------------------------------------------
		echo "<tr><td align=\"left\"><span class=\"testo_blu\">Note :&nbsp;</span></td>\n";
		echo "<td align=\"left\">";
		printf("%s</td></tr>", $dati['note']);
		echo "</table>";

		echo "<br><br><b> Visualizza tutti i dettagli e modifica Banca ==> &nbsp;<b><input type=\"submit\" name=\"codice\" value=\"$_codice\">\n";
		echo "</form>";
	


	echo "<br>";

	if ($CONTABILITA == "SI")
	{
		$_anno = date('Y');
		require "../../../setting/par_conta.inc.php";
		echo "<br>Visualizza la situazione cliente contabile == ><a href=\"../../contabilita/result_scheda.php?tipo_cf=B&codconto=$MASTRO_BANCHE$_codice&start=$_anno\">Clicca QUI !</a>\n";
		echo "<br><br>\n";
	}
	echo "</td></tr></table>"; //chiusura seconda tabella
// Fine tabella pagina principale -----------------------------------------------------------


	$conn->null;
	$conn = null;
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>