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

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

require $_percorso . "librerie/motore_anagrafiche.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "2")
{



	echo "<table with=\"100%\" border=\"0\" align=\"left\">\n";
	echo "<tr>";
// Inizio tabella pagina principale ----------------------------------------------------------

	echo "<td width=\"80%\" align=\"center\" valign=\"top\" class=\"foto\">\n";



	echo "<form action=\"risinsevett.php\" method=\"POST\">";
	echo "<table width=\"100%\" border=\"0\">";
	echo "<span class=\"testo_blu\"><br><b>Anagrafica vettori</b></span><br><br>";

	if ($_GET['azione'] == "nuovo")
	{
		$_submit = "inserisci";
		// CAMPO Codice ---------------------------------------------------------------------------------------
		echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Codice:&nbsp;</b></span></td>\n";
		echo "<td class=\"colonna\" align=\"left\"><input type=\"text\" name=\"codice\" size=\"7\" maxlength=\"6\">Campo alfanumerico di 6 caratteri</td><tr>\n";
	}
	else
	{
		$_submit = "Aggiorna";
		$_codice = $_POST['codice'];

		$dati = tabella_vettori("singolo", $_percorso, $_codice, $_parametri);

		// CAMPO Codice ---------------------------------------------------------------------------------------
		echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Codice:&nbsp;</b></span></td>\n";
		printf("<td class=\"colonna\" align=\"left\"><input type=\"radio\" name=\"codice\" value=\"%s\" checked>%s</td><tr>\n", $_codice, $_codice);
	}

// CAMPO ragione sociale 1 ---------------------------------------------------------------------------------------
	echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Ragione Sociale:&nbsp;</b></span></td>";
	printf("<td class=\"colonna\" align=\"left\"><input type=\"text\" name=\"vettore\" value=\"%s\" size=\"75\" maxlength=\"100\"></td></tr>\n", $dati['vettore']);

// CAMPO Indirizzo ---------------------------------------------------------------------------------------
	echo "<tr><td align=\"left\"><span class=\"testo_blu\">Indirizzo:&nbsp;</span></td>";
	printf("<td class=\"colonna\" align=\"left\"><input type=\"text\" name=\"indirizzo\" value=\"%s\" size=\"60\" maxlength=\"60\"></td>\n", $dati['indirizzo']);

// CAMPO Telefono -----------------------------------------------------------------------------------------
	echo "<tr><td align=\"left\"><span class=\"testo_blu\">Telefono :&nbsp;</span></td>";
	printf("<td class=\"colonna\" align=\"left\"><input type=\"text\" name=\"telefono\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>", $dati['telefono']);

// CAMPO Cell -----------------------------------------------------------------------------------------
	echo "<tr><td align=\"left\"><span class=\"testo_blu\">Cellulare :&nbsp;</span></td>";
	printf("<td class=\"colonna\" align=\"left\"><input type=\"text\" name=\"cell\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>", $dati['cell']);

// CAMPO Fax -----------------------------------------------------------------------------------------
	echo "<tr><td align=\"left\"><span class=\"testo_blu\">Fax :&nbsp;</span></td>";
	printf("<td class=\"colonna\" align=\"left\"><input type=\"text\" name=\"fax\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>", $dati['fax']);


// CAMPO email  ---------------------------------------------------------------------------------------
	echo "<tr><td align=\"left\"><span class=\"testo_blu\">E-mail generale:&nbsp;</span></td>";
	printf("<td class=\"colonna\" align=\"left\"><input type=\"text\" name=\"email\" value=\"%s\" size=\"75\" maxlength=\"80\"></td></tr>\n", $dati['email']);

// CAMPO sito internet ---------------------------------------------------------------------------------------
	echo "<tr><td align=\"left\"><span class=\"testo_blu\">Sito internet:&nbsp;</span></td>";
	printf("<td class=\"colonna\" align=\"left\"><input type=\"text\" name=\"web\" value=\"%s\" size=\"75\" maxlength=\"80\"></td></tr>\n", $dati['web']);

	// CAMPO collegamento alla ricerca del collo pagina contenenteil GET
	echo "<tr><td align=\"left\"><span class=\"testo_blu\">Link Traking:&nbsp;</span></td>";
	printf("<td class=\"colonna\" align=\"left\"><input type=\"text\" name=\"traking\" value=\"%s\" size=\"75\" maxlength=\"200\"></td></tr>\n", $dati['traking']);


// CAMPO note ---------------------------------------------------------------------------------------
	echo "<tr><td align=\"left\"><span class=\"testo_blu\">Note :&nbsp;</span></td>";
	printf("<td class=\"colonna\" align=\"left\"><textarea name=\"note\" cols=\"60\" rows=\"15\" WRAP=\"physical\">%s</textarea></td></tr>\n", $dati['note']);

// PULSANTI E CHIUSURA FORM -----------------------------------------------------------------------------------------
	echo "</table>\n<br><input type=\"submit\" name=\"azione\" value=\"$_submit\">&nbsp;<input type=\"submit\" name=\"azione\" value=\"Elimina\">\n";
	echo "</form>\n</td></tr></table>\n";
	echo "</td>\n</tr>\n";
// ************************************************************************************** -->
	echo "</table>\n";
// Fine tabella pagina princ
// Fine tabella pagina principale -----------------------------------------------------------
	
	$conn->null;
	$conn=null;
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>