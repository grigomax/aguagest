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
#menu_tendina($_cosa, $_percorso);
$_azione = $_GET['azione'];

if ($_SESSION['user']['anagrafiche'] > "2")
{
	echo "<table width=\"100%\" align=\"left\">";

	echo "<tr><td width=\"100\" valign=TOP >";

// Inizio tabella pagina principale ----------------------------------------------------------
	echo "<table width=\"100%\" cellspacing=\"0\" align=\"left\" cellpadding=\"4\" border=\"0\">\n";
	echo "<tr>";
	echo "<td width=\"85%\" align=\"center\" valign=\"top\" class=\"foto\">\n";

	if ($_azione == "modifica")
	{

		echo "<span class=\"testo_blu\"><h2>Modifica Anagrafica BANCA</h2></span>";

		$dati = tabella_banche("singola", $_POST['codice'], $_abi, $_cab, $_parametri);

	if($dati == "errore")
	{
		echo "<h2>Si è verificato un errore..</h2>\n";
		echo "<h2> ".$result[errori][descrizione] ."\n";
		exit;
	}

			echo "<form action=\"risinsebanca.php\" method=\"POST\">";
		echo "<table width=\"80%\" border=\"0\"";
// CAMPO Codice ---------------------------------------------------------------------------------------
		echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Codice:&nbsp;</b></span></td>\n";
		printf("<td class=\"colonna\" align=\"left\"><input type=\"radio\" name=\"codice\" value=\"%s\" checked>%s</td><tr>\n", $dati['codice'], $dati['codice']);
	}
	else
	{
		
		echo "<span class=\"testo_blu\"><h2>Inserimento Nuova BANCA</h2></span>";
		
		echo "<form action=\"risinsebanca.php\" method=\"POST\">";
		echo "<table width=\"80%\" border=\"0\"";
// CAMPO Codice ---------------------------------------------------------------------------------------
		echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Codice suggerito:&nbsp;</b></span></td>\n";
		echo "<td class=\"colonna\" align=\"left\"><input type=\"text\" name=\"codice\" size=\"3\" maxlength=\"2\"><font size=1> Inserire un codice Progressivo anche alfanumerico max 2 caratteri </font></td><tr>\n";
	}

        echo "<tr><td align=\"left\"><span class=\"testo_blu\">Disabilita banca :&nbsp;</span></td>\n";
        printf("<td align=\"left\"><input type=\"text\" value=\"%s\" name=\"es_selezione\" size=\"3\" maxlength=\"2\"> Se <b> SI </b> esclude dalla selezione a tendina</td><tr>", $dati['es_selezione']);

// CAMPO ragione sociale 1 ---------------------------------------------------------------------------------------
	echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Ragione Sociale:&nbsp;</b></span></td>";
	printf("<td class=\"colonna\" align=\"left\"><input type=\"text\" name=\"banca\" value=\"%s\" size=\"52\" maxlength=\"50\"></td></tr>\n", $dati['banca']);


// CAMPO Indirizzo ---------------------------------------------------------------------------------------
	echo "<tr><td align=\"left\"><span class=\"testo_blu\">Indirizzo:&nbsp;</span></td>";
	printf("<td align=\"left\"><input type=\"text\" name=\"indirizzo\" value=\"%s\" size=\"60\" maxlength=\"60\"></td>\n", $dati['indirizzo']);

// CAMPO Cap ---------------------------------------------------------------------------------------
	echo "<tr><td align=\"left\"><span class=\"testo_blu\">ABI :&nbsp;</span></td>";
	printf("<td align=\"left\"><input type=\"text\" name=\"abi\" value=\"%s\" size=\"6\" maxlength=\"5\"></td></tr>", $dati['abi']);

// CAMPO citt�---------------------------------------------------------------------------------------
	echo "<tr><td align=\"left\"><span class=\"testo_blu\">CAB&nbsp;</span></td>";
	printf("<td align=\"left\"><input type=\"text\" name=\"cab\" value=\"%s\" size=\"6\" maxlength=\"5\"></td></tr>", $dati['cab']);


// CAMPO Provincia ---------------------------------------------------------------------------------------
	echo "<tr><td align=\"left\"><span class=\"testo_blu\">Cin. :&nbsp;</span></td>";
	printf("<td align=\"left\"><input type=\"text\" name=\"cin\" value=\"%s\" size=\"2\" maxlength=\"1\"></td></tr>", $dati['cin']);

// CAMPO Nazione -----------------------------------------------------------------------------------------
	echo "<tr><td align=\"left\"><span class=\"testo_blu\">C/C :&nbsp;</span></td>";
	printf("<td align=\"left\"><input type=\"text\" name=\"cc\" value=\"%s\" size=\"20\" maxlength=\"12\"></td></tr>", $dati['cc']);

// CAMPO codfiscale -----------------------------------------------------------------------------------------
	echo "<tr><td align=\"left\"><span class=\"testo_blu\">IBAN :&nbsp;</span></td>";
	printf("<td align=\"left\"><input type=\"text\" name=\"iban\" value=\"%s\" size=\"5\" maxlength=\"4\"></td></tr>", $dati['iban']);

// CAMPO Partita iva -----------------------------------------------------------------------------------------
	echo "<tr><td align=\"left\"><span class=\"testo_blu\">Swift / BIC&nbsp;</span></td>";
	printf("<td align=\"left\"><input type=\"text\" name=\"swift\" value=\"%s\" size=\"14\" maxlength=\"13\"></td></tr>", $dati['swift']);

// CAMPO Telefono -----------------------------------------------------------------------------------------
	echo "<tr><td align=\"left\"><span class=\"testo_blu\">Telefono :&nbsp;</span></td>";
	printf("<td align=\"left\"><input type=\"text\" name=\"telefono\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>", $dati['telefono']);

// CAMPO Cell -----------------------------------------------------------------------------------------
	echo "<tr><td align=\"left\"><span class=\"testo_blu\">Cellulare :&nbsp;</span></td>";
	printf("<td align=\"left\"><input type=\"text\" name=\"cell\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>", $dati['cell']);

// CAMPO Fax -----------------------------------------------------------------------------------------
	echo "<tr><td align=\"left\"><span class=\"testo_blu\">Fax :&nbsp;</span></td>";
	printf("<td align=\"left\"><input type=\"text\" name=\"fax\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>", $dati['fax']);

// CAMPO note ---------------------------------------------------------------------------------------
	echo "<tr><td align=\"left\"><span class=\"testo_blu\">Note :&nbsp;</span></td>";
	printf("<td class=\"colonna\" align=\"left\"><textarea name=\"note\" cols=\"60\" rows=\"15\" WRAP=\"physical\">%s</textarea></td></tr>\n", $dati['note']);


// PULSANTI E CHIUSURA FORM -----------------------------------------------------------------------------------------
	echo "</table>\n<br>\n";

	if ($_azione == "modifica")
	{
		echo "<input type=\"submit\" name=\"azione\" value=\"Aggiorna\">&nbsp;<input type=\"submit\" name=\"azione\" value=\"Elimina\" onclick=\"if(!confirm('Sicuro di voler eliminare questa Banca ?')) return false;\">\n";
	}
	else
	{
		echo "<input type=\"submit\" name=\"azione\" value=\"Inserisci\">\n";
	}
	echo "</form>\n</td>\n";
	echo "</td>\n</tr>\n";
// ************************************************************************************** -->
	echo "</table>\n";


	$conn->null;
	$conn = null;
// Fine tabella pagina princ
// Fine tabella pagina principale -----------------------------------------------------------
}
else
{
	permessi_sessione($_cosa, $_percorso);
}
?>