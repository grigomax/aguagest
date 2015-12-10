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


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "1")
{

	// Inizio tabella pagina principale ---------------------------------------------------
	echo "<table width=\"100%\" cellspacing=\"0\" align=\"left\" cellpadding=\"4\">\n";
	echo "<tr>";
	echo "<td width=\"85%\" align=\"center\" valign=\"top\" class=\"foto\">\n";

	printf("<br><br><form action=\"risultato.php\" method=\"POST\">\n");
	echo "<table width=\"400\" border=\"0\"\n";

	// CAMPO DOVE ---------------------------------------------------------------------------------------
	echo "<tr><td width=\"200\" align=\"right\"><span class=\"testo_blu\">Dove:&nbsp;</span></td>\n";
	echo "<td class=\"colonna\" width=\"200\" align=\"center\">";
	echo "<select name=\"campi\">\n";
	echo "<option value=\"banca\">Istituto</option>\n";
	echo "<option value=\"codice\">Codice </option>\n";
	echo "<option value=\"telefono\">Telefono</option>\n";
	echo "<option value=\"cell\">Cellulare</option>\n";
	echo "<option value=\"fax\">Fax</option>\n";
	echo "<option value=\"abi\">abi</option>\n";
	echo "<option value=\"cab\">cab</option>\n";
	echo "<option value=\"cin\">Fax</option>\n";
	echo "<option value=\"cc\">abi</option>\n";
	echo "<option value=\"iban\">cab</option>\n";
	echo "<option value=\"swift\">cab</option>\n";
	echo "</select>\n";
	echo "</td></tr>\n";

	echo "<tr><td width=\"200\" align=\"right\"><span class=\"testo_blu\">Descrizione:&nbsp;</span></td>\n";
	printf("<td class=\"colonna\" width=\"200\" align=\"center\"><input type=\"text\" name=\"descrizione\" value=\"%s\" size=\"60\" maxlength=\"40\"></td></tr>\n", $_descrizione);


// PULSANTI E CHIUSURA FORM -----------------------------------------------------------------------------------------
	echo "</table>\n<input type=\"submit\" value=\"Cerca !\">\n";
	echo "</form>\n</td>\n";
	echo "</td>\n</tr>\n";
// ************************************************************************************** -->
	echo "</table>\n";
// Fine tabella pagina principale -----------------------------------------------------------
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>