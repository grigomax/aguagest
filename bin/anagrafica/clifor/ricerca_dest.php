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

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "1")
{

	$_tut = $_GET['tut'];

	if ($_tut == "c")
	{
// carico le varibili per la maschera clienti
		$_tipo = "clienti";
	}
	else
	{
		$_tipo = "fornitori";
	}


// Inizio tabella pagina principale ----------------------------------------------------------
	echo "<table width=\"100%\" cellspacing=\"0\" align=\"left\" cellpadding=\"4\">\n";
	echo "<td width=\"85%\" align=\"center\" valign=\"top\" class=\"foto\"><h1>Ricerca destinazioni $_tipo</h1>\n";

// ***********************************************************************************************************
//	printf( "<br>\n<span class=\"testo_blu\">%s</span>\n", $_testo );
	printf("<br><br><form action=\"risultato_dest.php?tut=$_tut\" method=\"POST\">\n");
	echo "<table width=\"400\" border=\"0\"\n";

// CAMPO DOVE ---------------------------------------------------------------------------------------
	echo "<tr><td width=\"200\" align=\"right\"><span class=\"testo_blu\">Dove:&nbsp;</span></td>\n";
	echo "<td class=\"colonna\" width=\"200\" align=\"center\">";
	echo "<select name=\"campi\">\n";
	echo "<option value=\"dragsoc\">Ragione Sociale</option>\n";
	echo "<option value=\"utente\">Codice </option>\n";
	echo "<option value=\"contatto\">contatto </option>\n";
	echo "<option value=\"telefono\">Telefono</option>\n";
	echo "<option value=\"cell\">Cellulare</option>\n";
	echo "<option value=\"fax\">Fax</option>\n";
	echo "<option value=\"iva\">Iva</option>\n";
	echo "<option value=\"piva\">Partita Iva</option>\n";
	echo "<option value=\"citta\">citta</option>\n";
	echo "<option value=\"prov\">Provincia</option>\n";
	echo "<option value=\"dragsoc\">Ragione sociale dest</option>\n";
	echo "<option value=\"dcitta\">Citta destinazione</option>\n";
	echo "<option value=\"dprov\">Provincia Destinazione</option>\n";
	echo "<option value=\"email\">e-mail</option>\n";
	echo "<option value=\"zona\">Zona</option>\n";
	echo "</select>\n";
	echo "</td></tr>\n";

	echo "<tr><td width=\"200\" align=\"right\"><span class=\"testo_blu\">Descrizione:&nbsp;</span></td>\n";
	echo "<td class=\"colonna\" width=\"200\" align=\"center\"><input type=\"text\" autofocus name=\"descrizione\" size=\"60\" maxlength=\"40\"></td></tr>\n";


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