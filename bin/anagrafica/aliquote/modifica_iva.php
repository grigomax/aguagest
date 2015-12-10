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
require "../../librerie/lib_html.php";
require "../../librerie/motore_anagrafiche.php";

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
	echo "<table with=\"100%\" align=\"left\" valign=\"TOP\">";
	echo "<tr><td valign=\"TOP\">";


	$dati = tabella_aliquota("singola", $_GET['codice'], $_percorso);


// Inizio tabella pagina principale ----------------------------------------------------------
	echo "<table width=\"100%\" cellspacing=\"0\" align=\"left\" cellpadding=\"4\">\n";
	echo "<tr>";
	echo "<td width=\"90%\" align=\"center\" valign=\"top\" class=\"foto\">\n";


	if ($_GET['azione'] == "nuova")
	{
		echo "<span class=\"testo_blu\"><br><b>Inserimento Aliquota iva</b></span><br>";

		echo "<form action=\"risinse_iva.php\" method=\"POST\">";
		echo "<table width=\"100%\" border=\"0\"";
// CAMPO Codice ---------------------------------------------------------------------------------------
		echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Codice:&nbsp;</b></span></td>\n";
		echo "<td class=\"colonna\" align=\"left\"><input type=\"text\" name=\"codice\" size=\"4\" maxlength=\"3\"> Inserire un codice alfanumerico max 3 caratteri </td><tr>\n";
	}
	else
	{
		echo "<span class=\"testo_blu\"><br><b>Modifica Aliquota iva</b></span><br>";

		echo "<form action=\"risinse_iva.php\" method=\"POST\">";
		echo "<table width=\"100%\" border=\"0\"";
// CAMPO Codice ---------------------------------------------------------------------------------------
		echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Codice:&nbsp;</b></span></td>\n";
		echo "<td class=\"colonna\" align=\"left\"><input type=\"radio\" name=\"codice\" value=\"$_GET[codice]\" checked>$_GET[codice]</td><tr>\n";
	}


// CAMPO descrizione---------------------------------------------------------------------------------------
	echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Descrizione:&nbsp;</b></span></td>";
	echo "<td class=\"colonna\" align=\"left\"><input type=\"text\" name=\"descrizione\" size=\"31\" maxlength=\"30\" value=\"$dati[descrizione]\"></td></tr>\n";

// CAMPO sconto ---------------------------------------------------------------------------------------/**/
	echo "<tr><td align=\"left\"><span class=\"testo_blu\">IVA CEE:&nbsp;</span></td>";
	echo "<td align=\"left\"><input type=\"text\" name=\"ivacee\"  size=\"2\" maxlength=\"1\" value=\"$dati[ivacee]\">Inserire S o N,<br>
		Se il codice ive viene utilizzato per le operazioni Europee </td>\n";

// CAMPO giorno fisso ---------------------------------------------------------------------------------------
	echo "<tr><td align=\"left\"><span class=\"testo_blu\">Tipo Codice :&nbsp;</span></td>";
	echo "<td align=\"left\"><input type=\"text\" name=\"eseniva\"  size=\"2\" maxlength=\"1\" value=\"$dati[eseniva]\">1 o 2, Mettere 1 per aliquote, 2 Per esenzioni</td></tr>";

// CAMPO esclusione primo mese ---------------------------------------------------------------------------------------
	echo "<tr><td align=\"left\"><span class=\"testo_blu\">% di aliquota :&nbsp;</span></td>";
	echo "<td align=\"left\"><input type=\"text\" name=\"aliquota\"  size=\"3\" maxlength=\"2\" value=\"$dati[aliquota]\">indicare il valore dell'aliquota, es 20</td></tr>";

// CAMPO esclusione secondo mese ---------------------------------------------------------------------------------------
	echo "<tr><td align=\"left\"><span class=\"testo_blu\">Ventilazione :&nbsp;</span></td>";
	echo "<td align=\"left\"><input type=\"text\" name=\"ventilazione\"  size=\"3\" maxlength=\"2\" value=\"$dati[ventilazione]\">Indicare il valore della percentuale della ventilazione per i corrispetivi </td></tr>";


	echo "<tr><td align=\"left\"><span class=\"testo_blu\">Indetraibilit&agrave; :&nbsp;</span></td>";
	echo "<td align=\"left\"><input type=\"text\" name=\"colonnacli\"  size=\"2\" maxlength=\"1\" value=\"$dati[colonnacli]\">Indicare 1 se iva indetraibile ma da calcolare</td></tr>";

	//echo "<tr><td align=\"left\"><span class=\"testo_blu\">Extra cee. ? :&nbsp;</span></td>";
	//echo "<td align=\"left\"><input type=\"text\" name=\"colonnafor\"  size=\"2\" maxlength=\"1\" value=\"$dati[colonnafor]\">Indicare il S o N - Se l'articolo è per utenti extracee</td></tr>";


	echo "<tr><td align=\"left\"><span class=\"testo_blu\">Esportatori Abituali ? :&nbsp;</span></td>";
	echo "<td align=\"left\"><input type=\"text\" name=\"plafond\"  size=\"2\" maxlength=\"1\" value=\"$dati[plafond]\"> Inserire S o N - se l'articolo è per gli esportatori abituali</td></tr>";

// CAMPO gg tra scdenza
	echo "<tr><td align=\"left\"><span class=\"testo_blu\">Extra cee. ? :&nbsp;</span></td>";
	echo "<td align=\"left\"><input type=\"text\" name=\"modello1012\"  size=\"2\" maxlength=\"1\" value=\"$dati[modello1012]\">Indicare il S o N - Se l'articolo è per utenti extracee</td></tr>";


	if($_GET['azione'] == "nuova")
	{
		echo "</table>\n<br><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" name=\"azione\" value=\"inserisci\">\n";
	}
	else
	{
		echo "</table>\n<br><input type=\"submit\" name=\"azione\" value=\"Aggiorna\">&nbsp;<input type=\"submit\" name=\"azione\" value=\"Elimina\">\n";
	}
// PULSANTI E CHIUSURA FORM -----------------------------------------------------------------------------------------
	
	echo "</form>\n</td>\n";
	echo "</td>\n</tr>\n";
// ************************************************************************************** -->
	echo "</table>\n";
// Fine tabella pagina princ
// Fine tabella pagina principale -----------------------------------------------------------
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>